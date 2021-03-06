<?php

namespace Drupal\libraries\ExternalLibrary\Version;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\libraries\Annotation\VersionDetector;

/**
 * Provides a plugin manager for library version detector plugins.
 *
 * @see \Drupal\libraries\ExternalLibrary\Version\VersionDetectorInterface
 */
class VersionDetectorManager extends DefaultPluginManager {

  /**
   * Constructs a version detector manager.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct('Plugin/libraries/VersionDetector', $namespaces, $module_handler, VersionDetectorInterface::class, VersionDetector::class);
    $this->alterInfo('libraries_version_detector_info');
    $this->setCacheBackend($cache_backend, 'libraries_version_detector_info');
  }

}
