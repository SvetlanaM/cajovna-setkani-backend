<?php

namespace Drupal\token\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Helper test class with some added functions for testing.
 */
abstract class TokenTestBase extends WebTestBase {

  use TokenTestTrait;

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['path', 'token', 'token_module_test'];

}
