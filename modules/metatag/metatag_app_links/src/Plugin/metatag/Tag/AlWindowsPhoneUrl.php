<?php

namespace Drupal\metatag_app_links\Plugin\metatag\Tag;

use Drupal\metatag\Plugin\metatag\Tag\MetaPropertyBase;

/**
 * The AppLinks Windows Phone app URL scheme meta tag.
 *
 * @MetatagTag(
 *   id = "al_windows_phone_url",
 *   label = @Translation("Windows Phone app URL scheme"),
 *   description = @Translation("A custom scheme for the iOS app. <strong>This attribute is required by the app Links specification.</strong>"),
 *   name = "al:windows_phone:url",
 *   group = "app_links",
 *   weight = 1,
 *   type = "string",
 *   secure = FALSE,
 *   multiple = FALSE
 * )
 */
class AlWindowsPhoneUrl extends MetaPropertyBase {
  // Nothing here yet. Just a placeholder class for a plugin.
}
