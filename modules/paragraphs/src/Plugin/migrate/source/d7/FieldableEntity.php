<?php

namespace Drupal\paragraphs\Plugin\migrate\source\d7;

use Drupal\migrate_drupal\Plugin\migrate\source\d7\FieldableEntity as MigrateFieldableEntity;
use Drupal\Component\Plugin\ConfigurablePluginInterface;
use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\State\StateInterface;
use Drupal\migrate\Plugin\MigrationInterface;

/**
 * Base Class for Paragraphs FieldableEntity migrate source plugins.
 *
 * Add and implement Configurable Plugin interface to
 * Drupal\migrate_drupal\Plugin\migrate\source\d7\FieldableEntity.
 */
abstract class FieldableEntity extends MigrateFieldableEntity implements ConfigurablePluginInterface {

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, MigrationInterface $migration, StateInterface $state, EntityManagerInterface $entity_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $migration, $state, $entity_manager);
    $this->setConfiguration($configuration);
  }

  /**
   * {@inheritdoc}
   */
  public function getConfiguration() {
    return $this->configuration;
  }

  /**
   * {@inheritdoc}
   */
  public function setConfiguration(array $configuration) {
    $this->configuration = NestedArray::mergeDeep($this->defaultConfiguration(), $configuration);
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [];
  }

}
