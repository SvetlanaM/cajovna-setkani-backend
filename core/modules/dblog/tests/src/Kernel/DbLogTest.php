<?php

namespace Drupal\Tests\dblog\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Drupal\Tests\dblog\Functional\FakeLogEntries;

/**
 * Generate events and verify dblog entries.
 *
 * @group dblog
 */
class DbLogTest extends KernelTestBase {

  use FakeLogEntries;

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['dblog', 'system'];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->installSchema('dblog', ['watchdog']);
    $this->installSchema('system', ['key_value_expire', 'sequences']);
    $this->installConfig(['system']);
  }

  /**
   * Tests that cron correctly applies the database log row limit.
   */
  public function testDbLogCron() {
    $row_limit = 100;
    // Generate additional log entries.
    $this->generateLogEntries($row_limit + 10);
    // Verify that the database log row count exceeds the row limit.
    $count = db_query('SELECT COUNT(wid) FROM {watchdog}')->fetchField();
    $this->assertGreaterThan($row_limit, $count, format_string('Dblog row count of @count exceeds row limit of @limit', ['@count' => $count, '@limit' => $row_limit]));

    // Get the number of enabled modules. Cron adds a log entry for each module.
    $list = $this->container->get('module_handler')->getImplementations('cron');
    $module_count = count($list);
    $cron_detailed_count = $this->runCron();
    $this->assertEquals($module_count + 2, $cron_detailed_count, format_string('Cron added @count of @expected new log entries', ['@count' => $cron_detailed_count, '@expected' => $module_count + 2]));

    // Test disabling of detailed cron logging.
    $this->config('system.cron')->set('logging', 0)->save();
    $cron_count = $this->runCron();
    $this->assertEquals(1, $cron_count, format_string('Cron added @count of @expected new log entries', ['@count' => $cron_count, '@expected' => 1]));
  }

  /**
   * Runs cron and returns number of new log entries.
   *
   * @return int
   *   Number of new watchdog entries.
   */
  private function runCron() {
    // Get last ID to compare against; log entries get deleted, so we can't
    // reliably add the number of newly created log entries to the current count
    // to measure number of log entries created by cron.
    $last_id = db_query('SELECT MAX(wid) FROM {watchdog}')->fetchField();

    // Run a cron job.
    $this->container->get('cron')->run();

    // Get last ID after cron was run.
    $current_id = db_query('SELECT MAX(wid) FROM {watchdog}')->fetchField();

    return $current_id - $last_id;
  }

}
