<?php

namespace Drupal\Tests\language\Functional\Hal;

use Drupal\Tests\language\Functional\Rest\ConfigurableLanguageResourceTestBase;
use Drupal\Tests\rest\Functional\CookieResourceTestTrait;

/**
 * @group hal
 */
class ConfigurableLanguageHalJsonCookieTest extends ConfigurableLanguageResourceTestBase {

  use CookieResourceTestTrait;

  /**
   * {@inheritdoc}
   */
  public static $modules = ['hal'];

  /**
   * {@inheritdoc}
   */
  protected static $format = 'hal_json';

  /**
   * {@inheritdoc}
   */
  protected static $mimeType = 'application/hal+json';

  /**
   * {@inheritdoc}
   */
  protected static $auth = 'cookie';

}
