<?php

namespace Drupal\Tests\media\Kernel;

use Drupal\media\Entity\Media;

/**
 * Tests Media.
 *
 * @group media
 */
class MediaTest extends MediaKernelTestBase {

  /**
   * Tests various aspects of a media item.
   */
  public function testEntity() {
    $media = Media::create(['bundle' => $this->testMediaType->id()]);

    $this->assertSame($media, $media->setOwnerId($this->user->id()), 'setOwnerId() method returns its own entity.');
  }

  /**
   * Tests the Media "name" base field behavior.
   */
  public function testNameBaseField() {
    /** @var \Drupal\Core\Field\BaseFieldDefinition[] $field_definitions */
    $field_definitions = $this->container->get('entity_field.manager')
      ->getBaseFieldDefinitions('media');

    // Ensure media name is configurable on manage display.
    $this->assertTrue($field_definitions['name']->isDisplayConfigurable('view'));
    // Ensure it is not visible by default.
    $this->assertSame($field_definitions['name']->getDisplayOptions('view'), ['region' => 'hidden']);
  }

  /**
   * Tests the legacy method used as the default entity owner.
   *
   * @group legacy
   * @expectedDeprecation The ::getCurrentUserId method is deprecated in 8.6.x and will be removed before 9.0.0.
   */
  public function testGetCurrentUserId() {
    $this->assertEquals(['1'], Media::getCurrentUserId());
  }

}
