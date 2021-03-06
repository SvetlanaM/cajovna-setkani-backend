<?php

namespace Drupal\Tests\block_content\Kernel;

use Drupal\block_content\Entity\BlockContent;
use Drupal\block_content\Entity\BlockContentType;
use Drupal\Component\Plugin\PluginBase;
use Drupal\KernelTests\KernelTestBase;

/**
 * Tests that deleting a block clears the cached definitions.
 *
 * @group block_content
 */
class BlockContentDeletionTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = ['block', 'block_content', 'system', 'user'];

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
    $this->installEntitySchema('user');
    $this->installEntitySchema('block_content');
  }

  /**
   * Tests deleting a block_content updates the discovered block plugin.
   */
  public function testDeletingBlockContentShouldClearPluginCache() {
    // Create a block content type.
    $block_content_type = BlockContentType::create([
      'id' => 'spiffy',
      'label' => 'Mucho spiffy',
      'description' => "Provides a block type that increases your site's spiffiness by upto 11%",
    ]);
    $block_content_type->save();
    // And a block content entity.
    $block_content = BlockContent::create([
      'info' => 'Spiffy prototype',
      'type' => 'spiffy',
    ]);
    $block_content->save();

    // Make sure the block content provides a derivative block plugin in the
    // block repository.
    /** @var \Drupal\Core\Block\BlockManagerInterface $block_manager */
    $block_manager = $this->container->get('plugin.manager.block');
    $plugin_id = 'block_content' . PluginBase::DERIVATIVE_SEPARATOR . $block_content->uuid();
    $this->assertTrue($block_manager->hasDefinition($plugin_id));

    // Now delete the block content entity.
    $block_content->delete();
    // The plugin should no longer exist.
    $this->assertFalse($block_manager->hasDefinition($plugin_id));
  }

}
