<?php

namespace Drupal\field\Plugin\migrate\source\d6;

use Drupal\migrate_drupal\Plugin\migrate\source\DrupalSqlBase;

/**
 * Gets field label and description translations.
 *
 * @MigrateSource(
 *   id = "d6_field_instance_label_description_translation",
 *   source_module = "i18ncck"
 * )
 */
class FieldLabelDescriptionTranslation extends DrupalSqlBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    // Get translations for field labels and descriptions.
    $query = $this->select('i18n_strings', 'i18n')
      ->fields('i18n', ['property', 'objectid', 'type'])
      ->fields('lt', ['lid', 'translation', 'language'])
      ->condition('i18n.type', 'field')
      ->isNotNull('language')
      ->isNotNull('translation');
    $condition = $query->orConditionGroup()
      ->condition('property', 'widget_label')
      ->condition('property', 'widget_description');
    $query->condition($condition);
    $query->leftJoin('locales_target', 'lt', 'lt.lid = i18n.lid');

    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    return [
      'property' => $this->t('Profile field ID.'),
      'lid' => $this->t('Locales target language ID.'),
      'language' => $this->t('Language for this field.'),
      'translation' => $this->t('Translation of either the title or explanation.'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    $ids['property']['type'] = 'string';
    $ids['language']['type'] = 'string';
    $ids['lid']['type'] = 'integer';
    $ids['lid']['alias'] = 'lt';
    return $ids;
  }

}
