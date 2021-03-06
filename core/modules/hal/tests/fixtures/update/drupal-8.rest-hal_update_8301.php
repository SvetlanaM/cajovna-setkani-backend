<?php

/**
 * @file
 * Contains database additions to drupal-8.bare.standard.php.gz for testing the
 * upgrade path of hal_update_8301().
 */

use Drupal\Core\Database\Database;

$connection = Database::getConnection();

// Set the schema version.
$connection->insert('key_value')
  ->fields([
    'collection' => 'system.schema',
    'name' => 'rest',
    'value' => 'i:8203;',
  ])
  ->execute();

// Update core.extension.
$extensions = $connection->select('config')
  ->fields('config', ['data'])
  ->condition('collection', '')
  ->condition('name', 'core.extension')
  ->execute()
  ->fetchField();
$extensions = unserialize($extensions);
$extensions['module']['rest'] = 0;
$connection->update('config')
  ->fields([
    'data' => serialize($extensions),
  ])
  ->condition('collection', '')
  ->condition('name', 'core.extension')
  ->execute();

$connection->merge('key_value')
  ->condition('collection', 'entity.definitions.installed')
  ->condition('name', 'rest_resource_config.entity_type')
  ->fields([
    'value' => 'O:42:"Drupal\Core\Config\Entity\ConfigEntityType":39:{s:16:" * config_prefix";s:8:"resource";s:15:" * static_cache";b:0;s:14:" * lookup_keys";a:1:{i:0;s:4:"uuid";}s:16:" * config_export";a:4:{i:0;s:2:"id";i:1;s:9:"plugin_id";i:2;s:11:"granularity";i:3;s:13:"configuration";}s:21:" * mergedConfigExport";a:0:{}s:15:" * render_cache";b:1;s:19:" * persistent_cache";b:1;s:14:" * entity_keys";a:6:{s:2:"id";s:2:"id";s:8:"revision";s:0:"";s:6:"bundle";s:0:"";s:8:"langcode";s:8:"langcode";s:16:"default_langcode";s:16:"default_langcode";s:4:"uuid";s:4:"uuid";}s:5:" * id";s:20:"rest_resource_config";s:11:" * provider";s:4:"rest";s:8:" * class";s:37:"Drupal\rest\Entity\RestResourceConfig";s:16:" * originalClass";N;s:11:" * handlers";a:2:{s:6:"access";s:45:"Drupal\Core\Entity\EntityAccessControlHandler";s:7:"storage";s:45:"Drupal\Core\Config\Entity\ConfigEntityStorage";}s:19:" * admin_permission";s:25:"administer rest resources";s:25:" * permission_granularity";s:11:"entity_type";s:8:" * links";a:0:{}s:17:" * label_callback";s:18:"getLabelFromPlugin";s:21:" * bundle_entity_type";N;s:12:" * bundle_of";N;s:15:" * bundle_label";N;s:13:" * base_table";N;s:22:" * revision_data_table";N;s:17:" * revision_table";N;s:13:" * data_table";N;s:15:" * translatable";b:0;s:8:" * label";O:48:"Drupal\Core\StringTranslation\TranslatableMarkup":3:{s:9:" * string";s:20:"REST resource config";s:12:" * arguments";a:0:{}s:10:" * options";a:0:{}}s:17:" * label_singular";s:0:"";s:15:" * label_plural";s:0:"";s:14:" * label_count";a:0:{}s:15:" * uri_callback";N;s:8:" * group";s:13:"configuration";s:14:" * group_label";O:48:"Drupal\Core\StringTranslation\TranslatableMarkup":3:{s:9:" * string";s:13:"Configuration";s:12:" * arguments";a:0:{}s:10:" * options";a:1:{s:7:"context";s:17:"Entity type group";}}s:22:" * field_ui_base_route";N;s:26:" * common_reference_target";b:0;s:22:" * list_cache_contexts";a:0:{}s:18:" * list_cache_tags";a:1:{i:0;s:32:"config:rest_resource_config_list";}s:14:" * constraints";a:0:{}s:13:" * additional";a:0:{}s:20:" * stringTranslation";N;}',
    'name' => 'rest_resource_config.entity_type',
    'collection' => 'entity.definitions.installed',
  ])
  ->execute();

$connection->merge('key_value')
  ->condition('collection', 'post_update')
  ->condition('name', 'existing_updates')
  ->fields([
    'value' => 'a:19:{i:0;s:64:"system_post_update_recalculate_configuration_entity_dependencies";i:1;s:43:"field_post_update_email_widget_size_setting";i:2;s:50:"field_post_update_entity_reference_handler_setting";i:3;s:46:"field_post_update_save_custom_storage_property";i:4;s:42:"image_post_update_image_style_dependencies";i:5;s:54:"block_post_update_disable_blocks_with_missing_contexts";i:6;s:56:"editor_post_update_clear_cache_for_file_reference_filter";i:7;s:62:"contact_post_update_add_message_redirect_field_to_contact_form";i:8;s:39:"views_post_update_boolean_filter_values";i:9;s:46:"views_post_update_cleanup_duplicate_views_data";i:10;s:46:"views_post_update_field_formatter_dependencies";i:11;s:41:"views_post_update_serializer_dependencies";i:12;s:36:"views_post_update_taxonomy_index_tid";i:13;s:46:"views_post_update_update_cacheability_metadata";i:14;s:53:"rest_post_update_create_rest_resource_config_entities";i:15;s:37:"rest_post_update_resource_granularity";i:16;s:40:"block_post_update_disabled_region_update";i:17;s:42:"block_post_update_fix_negate_in_conditions";i:18;s:48:"system_post_update_add_region_to_entity_displays";}',
    'name' => 'existing_updates',
    'collection' => 'post_update',
  ])
  ->execute();

// Install the 'rest.settings' config.
$config = [
  'link_domain' => 'http://example.com',
  'bc_entity_resource_permissions' => FALSE,
];
$data = $connection->insert('config')
  ->fields([
    'name' => 'rest.settings',
    'data' => serialize($config),
    'collection' => '',
  ])
  ->execute();
