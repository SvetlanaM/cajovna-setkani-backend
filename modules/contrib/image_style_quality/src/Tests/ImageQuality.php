<?php
/**
 * @file
 * Test that image style quality works.
 */
namespace Drupal\image_style_quality\Tests;

use Drupal\image\Entity\ImageStyle;
use Drupal\simpletest\WebTestBase;

/**
 * Test image style quality.
 *
 * @group image_style_quality
 */
class ImageQuality extends WebTestBase {
  public static $modules = [
    'image_style_quality',
    'image_style_quality_test',
  ];

  /**
   * Test that the image effect manipulates filesizes.
   */
  public function testImageStyle() {
    $original_image = drupal_get_path('module', 'image_style_quality_test') . '/images/original.jpg';
    $large_derivative_uri = $this->publicFilesDirectory . '/large-derivative.jpg';
    $small_derivative_uri = $this->publicFilesDirectory . '/small-derivative.jpg';

    ImageStyle::load('high_quality')->createDerivative($original_image, $large_derivative_uri);
    ImageStyle::load('low_quality')->createDerivative($original_image, $small_derivative_uri);

    $large_filesize = filesize($large_derivative_uri);
    $small_filesize = filesize($small_derivative_uri);

    $this->assertTrue($large_filesize > $small_filesize * 10, 'The compressed image style reduced the filesize of an image by a factor of 10.');
  }
}
