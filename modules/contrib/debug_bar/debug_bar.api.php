<?php

/**
 * @file
 * Hooks provided by the Debug bar module.
 */

/**
 * @addtogroup hooks
 * @{
 */

/**
 * Alters the Debug bar items.
 *
 * @param array[] $items
 *   Debug bar items.
 */
function hook_debug_bar_items_alter(array &$items) {
  $items['example'] = [
    'title' => t('Example'),
    'icon_path' => base_path() . drupal_get_path('module', 'example') . '/images/example.png',
    'attributes' => ['title' => t('Example')],
    'weight' => 100,
    'access' => TRUE,
  ];
}

/**
 * @} End of "addtogroup hooks".
 */
