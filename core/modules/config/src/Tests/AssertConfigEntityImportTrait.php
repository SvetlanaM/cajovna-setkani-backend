<?php

namespace Drupal\config\Tests;

@trigger_error('The ' . __NAMESPACE__ . '\AssertConfigEntityImportTrait is deprecated in Drupal 8.4.1 and will be removed before Drupal 9.0.0. Instead, use \Drupal\Tests\config\Traits\AssertConfigEntityImportTrait. See https://www.drupal.org/node/2916197.', E_USER_DEPRECATED);

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides test assertions for testing config entity synchronization.
 *
 * Can be used by test classes that extend \Drupal\simpletest\WebTestBase or
 * \Drupal\KernelTests\KernelTestBase.
 *
 * @deprecated in Drupal 8.4.1 and will be removed before Drupal 9.0.0.
 *   Use \Drupal\Tests\config\Traits\AssertConfigEntityImportTrait.
 *
 * @see https://www.drupal.org/node/2916197
 */
trait AssertConfigEntityImportTrait {

  /**
   * Asserts that a config entity can be imported without changing it.
   *
   * @param \Drupal\Core\Config\Entity\ConfigEntityInterface $entity
   *   The config entity to test importing.
   */
  public function assertConfigEntityImport(ConfigEntityInterface $entity) {
    // Save original config information.
    $entity_uuid = $entity->uuid();
    $entity_type_id = $entity->getEntityTypeId();
    $original_data = $entity->toArray();
    // Copy everything to sync.
    $this->copyConfig(\Drupal::service('config.storage'), \Drupal::service('config.storage.sync'));
    // Delete the configuration from active. Don't worry about side effects of
    // deleting config like fields cleaning up field storages. The coming import
    // should recreate everything as necessary.
    $entity->delete();
    $this->configImporter()->reset()->import();
    $imported_entity = \Drupal::service('entity.repository')->loadEntityByUuid($entity_type_id, $entity_uuid);
    $this->assertIdentical($original_data, $imported_entity->toArray());
  }

}
