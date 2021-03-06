<?php

namespace Drupal\Core\Extension;

use Composer\Semver\Semver;
use Drupal\Component\Serialization\Exception\InvalidDataTypeException;
use Drupal\Core\Serialization\Yaml;

/**
 * Parses dynamic .info.yml files that might change during the page request.
 */
class InfoParserDynamic implements InfoParserInterface {

  /**
   * The earliest Drupal version that supports the 'core_version_requirement'.
   */
  const FIRST_CORE_VERSION_REQUIREMENT_SUPPORTED_VERSION = '8.7.7';

  /**
   * {@inheritdoc}
   */
  public function parse($filename) {
    if (!file_exists($filename)) {
      $parsed_info = [];
    }
    else {
      try {
        $parsed_info = Yaml::decode(file_get_contents($filename));
      }
      catch (InvalidDataTypeException $e) {
        throw new InfoParserException("Unable to parse $filename " . $e->getMessage());
      }
      $missing_keys = array_diff($this->getRequiredKeys(), array_keys($parsed_info));
      if (!empty($missing_keys)) {
        throw new InfoParserException('Missing required keys (' . implode(', ', $missing_keys) . ') in ' . $filename);
      }
      if ($parsed_info['type'] === 'profile' && isset($parsed_info['core_version_requirement'])) {
        // @todo Support the 'core_version_requirement' key in profiles in
        //   https://www.drupal.org/node/3070401.
        throw new InfoParserException("The 'core_version_requirement' key is not supported in profiles in $filename");
      }
      if (!isset($parsed_info['core']) && !isset($parsed_info['core_version_requirement'])) {
        throw new InfoParserException("The 'core' or the 'core_version_requirement' key must be present in " . $filename);
      }
      if (isset($parsed_info['core']) && !preg_match("/^\d\.x$/", $parsed_info['core'])) {
        throw new InfoParserException("Invalid 'core' value \"{$parsed_info['core']}\" in " . $filename);
      }
      if (isset($parsed_info['core_version_requirement'])) {
        $supports_pre_core_version_requirement_version = static::isConstraintSatisfiedByPreviousVersion($parsed_info['core_version_requirement'], static::FIRST_CORE_VERSION_REQUIREMENT_SUPPORTED_VERSION);
        // If the 'core_version_requirement' constraint does not satisfy any
        // Drupal 8 versions before 8.7.7 then 'core' cannot be set or it will
        // effectively support all versions of Drupal 8 because
        // 'core_version_requirement' will be ignored in previous versions.
        if (!$supports_pre_core_version_requirement_version && isset($parsed_info['core'])) {
          throw new InfoParserException("The 'core_version_requirement' constraint ({$parsed_info['core_version_requirement']}) requires the 'core' key not be set in " . $filename);
        }
        // 'core_version_requirement' can not be used to specify Drupal 8
        // versions before 8.7.7 because these versions do not use the
        // 'core_version_requirement' key. Do not throw the exception if the
        // constraint also is satisfied by 8.0.0-alpha1 to allow constraints
        // such as '^8' or '^8 || ^9'.
        if ($supports_pre_core_version_requirement_version && !Semver::satisfies('8.0.0-alpha1', $parsed_info['core_version_requirement'])) {
          throw new InfoParserException("The 'core_version_requirement' can not be used to specify compatibility for a specific version before " . static::FIRST_CORE_VERSION_REQUIREMENT_SUPPORTED_VERSION . " in $filename");
        }
      }

      // Determine if the extension is compatible with the current version of
      // Drupal core.
      $core_version_constraint = isset($parsed_info['core_version_requirement']) ? $parsed_info['core_version_requirement'] : $parsed_info['core'];
      $parsed_info['core_incompatible'] = !Semver::satisfies(\Drupal::VERSION, $core_version_constraint);
      if (isset($parsed_info['version']) && $parsed_info['version'] === 'VERSION') {
        $parsed_info['version'] = \Drupal::VERSION;
      }
      // Special backwards compatible handling profiles and their 'dependencies'
      // key.
      if ($parsed_info['type'] === 'profile' && isset($parsed_info['dependencies']) && !array_key_exists('install', $parsed_info)) {
        // Only trigger the deprecation message if we are actually using the
        // profile with the missing 'install' key. This avoids triggering the
        // deprecation when scanning all the available install profiles.
        global $install_state;
        if (isset($install_state['parameters']['profile'])) {
          $pattern = '@' . preg_quote(DIRECTORY_SEPARATOR . $install_state['parameters']['profile'] . '.info.yml') . '$@';
          if (preg_match($pattern, $filename)) {
            @trigger_error("The install profile $filename only implements a 'dependencies' key. As of Drupal 8.6.0 profile's support a new 'install' key for modules that should be installed but not depended on. See https://www.drupal.org/node/2952947.", E_USER_DEPRECATED);
          }
        }
        // Move dependencies to install so that if a profile has both
        // dependencies and install then dependencies are real.
        $parsed_info['install'] = $parsed_info['dependencies'];
        $parsed_info['dependencies'] = [];
      }
    }
    return $parsed_info;
  }

  /**
   * Returns an array of keys required to exist in .info.yml file.
   *
   * @return array
   *   An array of required keys.
   */
  protected function getRequiredKeys() {
    return ['type', 'name'];
  }

  /**
   * Determines if a constraint is satisfied by earlier versions of Drupal 8.
   *
   * @param string $constraint
   *   A core semantic version constraint.
   * @param string $version
   *   A core version.
   *
   * @return bool
   *   TRUE if the constraint is satisfied by a core version prior to the
   *   provided version.
   */
  protected static function isConstraintSatisfiedByPreviousVersion($constraint, $version) {
    static $evaluated_constraints = [];
    // Any particular constraint and version combination only needs to be
    // evaluated once.
    if (!isset($evaluated_constraints[$constraint][$version])) {
      $evaluated_constraints[$constraint][$version] = FALSE;
      foreach (static::getAllPreviousCoreVersions($version) as $previous_version) {
        if (Semver::satisfies($previous_version, $constraint)) {
          $evaluated_constraints[$constraint][$version] = TRUE;
          // The constraint only has to satisfy one previous version so break
          // when the first one is found.
          break;
        }
      }
    }
    return $evaluated_constraints[$constraint][$version];
  }

  /**
   * Gets all the versions of Drupal 8 before a specific version.
   *
   * @param string $version
   *   The version to get versions before.
   *
   * @return array
   *   All of the applicable Drupal 8 releases.
   */
  protected static function getAllPreviousCoreVersions($version) {
    static $versions_lists = [];
    // Check if list of previous versions for the specified version has already
    // been created.
    if (empty($versions_lists[$version])) {
      // Loop through all minor versions including 8.7.
      foreach (range(0, 7) as $minor) {
        // The largest patch number in a release was 17 in 8.6.17. Use 27 to
        // leave room for future security releases.
        foreach (range(0, 27) as $patch) {
          $patch_version = "8.$minor.$patch";
          if ($patch_version === $version) {
            // Reverse the order of the versions so that they will be evaluated
            // from the most recent versions first.
            $versions_lists[$version] = array_reverse($versions_lists[$version]);
            return $versions_lists[$version];
          }
          if ($patch === 0) {
            // If this is a '0' patch release like '8.1.0' first create the
            // pre-release versions such as '8.1.0-alpha1' and '8.1.0-rc1'.
            foreach (['alpha', 'beta', 'rc'] as $prerelease) {
              // The largest prerelease number was  in 8.0.0-beta16.
              foreach (range(0, 16) as $prerelease_number) {
                $versions_lists[$version][] = "$patch_version-$prerelease$prerelease_number";
              }
            }
          }
          $versions_lists[$version][] = $patch_version;
        }
      }
    }
    return $versions_lists[$version];
  }

}
