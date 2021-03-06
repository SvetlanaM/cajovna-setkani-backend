<?php

namespace Drupal\config_translation\Controller;

use Drupal\Core\DependencyInjection\DeprecatedServicePropertyTrait;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines the config translation list builder for field entities.
 */
class ConfigTranslationFieldListBuilder extends ConfigTranslationEntityListBuilder {
  use DeprecatedServicePropertyTrait;

  /**
   * {@inheritdoc}
   */
  protected $deprecatedProperties = ['entityManager' => 'entity.manager'];

  /**
   * The name of the entity type the fields are attached to.
   *
   * @var string
   */
  protected $baseEntityType = '';

  /**
   * An array containing the base entity type's definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeInterface
   */
  protected $baseEntityInfo;

  /**
   * The bundle info for the base entity type.
   *
   * @var array
   */
  protected $baseEntityBundles = [];

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The entity bundle info.
   *
   * @var \Drupal\Core\Entity\EntityTypeBundleInfoInterface
   */
  protected $entityTypeBundleInfo;

  /**
   * {@inheritdoc}
   */
  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    $entity_type_manager = $container->get('entity_type.manager');
    return new static(
      $entity_type,
      $entity_type_manager->getStorage($entity_type->id()),
      $entity_type_manager
    );
  }

  /**
   * Constructs a new ConfigTranslationFieldListBuilder object.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The entity type definition.
   * @param \Drupal\Core\Entity\EntityStorageInterface $storage
   *   The entity storage class.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Entity\EntityTypeBundleInfoInterface $entity_type_bundle_info
   *   The entity type bundle info.
   */
  public function __construct(EntityTypeInterface $entity_type, EntityStorageInterface $storage, EntityTypeManagerInterface $entity_type_manager, EntityTypeBundleInfoInterface $entity_type_bundle_info = NULL) {
    parent::__construct($entity_type, $storage);
    $this->entityTypeManager = $entity_type_manager;
    if (!$entity_type_bundle_info) {
      @trigger_error('Calling ConfigTranslationFieldListBuilder::__construct() with the $entity_type_bundle_info argument is supported in drupal:8.7.0 and will be required before drupal:9.0.0. See https://www.drupal.org/node/2549139.', E_USER_DEPRECATED);
      $entity_type_bundle_info = \Drupal::service('entity_type.bundle.info');
    }
    $this->entityTypeBundleInfo = $entity_type_bundle_info;
  }

  /**
   * {@inheritdoc}
   */
  public function setMapperDefinition($mapper_definition) {
    $this->baseEntityType = $mapper_definition['base_entity_type'];
    $this->baseEntityInfo = $this->entityTypeManager->getDefinition($this->baseEntityType);
    $this->baseEntityBundles = $this->entityTypeBundleInfo->getBundleInfo($this->baseEntityType);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function load() {
    // It is not possible to use the standard load method, because this needs
    // all field entities only for the given baseEntityType.
    $ids = \Drupal::entityQuery('field_config')
      ->condition('id', $this->baseEntityType . '.', 'STARTS_WITH')
      ->execute();
    return $this->storage->loadMultiple($ids);
  }

  /**
   * {@inheritdoc}
   */
  public function getFilterLabels() {
    $info = parent::getFilterLabels();
    $bundle = $this->baseEntityInfo->getBundleLabel() ?: $this->t('Bundle');
    $bundle = mb_strtolower($bundle);

    $info['placeholder'] = $this->t('Enter field or @bundle', ['@bundle' => $bundle]);
    $info['description'] = $this->t('Enter a part of the field or @bundle to filter by.', ['@bundle' => $bundle]);

    return $info;
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['label'] = [
      'data' => $entity->label(),
      'class' => 'table-filter-text-source',
    ];

    if ($this->displayBundle()) {
      $bundle = $entity->get('bundle');
      $row['bundle'] = [
        'data' => $this->baseEntityBundles[$bundle]['label'],
        'class' => 'table-filter-text-source',
      ];
    }

    return $row + parent::buildRow($entity);
  }

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['label'] = $this->t('Field');
    if ($this->displayBundle()) {
      $header['bundle'] = $this->baseEntityInfo->getBundleLabel() ?: $this->t('Bundle');
    }
    return $header + parent::buildHeader();
  }

  /**
   * Controls the visibility of the bundle column on field list pages.
   *
   * @return bool
   *   Whenever the bundle is displayed or not.
   */
  public function displayBundle() {
    // The bundle key is explicitly defined in the entity definition.
    if ($this->baseEntityInfo->getKey('bundle')) {
      return TRUE;
    }

    // There is more than one bundle defined.
    if (count($this->baseEntityBundles) > 1) {
      return TRUE;
    }

    // The defined bundle ones not match the entity type name.
    if (!empty($this->baseEntityBundles) && !isset($this->baseEntityBundles[$this->baseEntityType])) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function sortRows($a, $b) {
    return $this->sortRowsMultiple($a, $b, ['bundle', 'label']);
  }

}
