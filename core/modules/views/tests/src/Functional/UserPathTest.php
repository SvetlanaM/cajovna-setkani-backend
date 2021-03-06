<?php

namespace Drupal\Tests\views\Functional;

/**
 * Tests overriding user paths using wildcards.
 *
 * @group views
 */
class UserPathTest extends ViewTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = ['views', 'user'];

  /**
   * The test views to use.
   *
   * @var array
   */
  public static $testViews = ['test_user_path'];

  /**
   * Tests if the login page is still available when using a wildcard path.
   */
  public function testUserLoginPage() {
    $this->drupalGet('user/login');
    $this->assertSession()->statusCodeEquals(200);
  }

}
