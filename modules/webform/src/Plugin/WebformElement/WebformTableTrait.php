<?php

namespace Drupal\webform\Plugin\WebformElement;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\OptGroup;
use Drupal\webform\Utility\WebformArrayHelper;
use Drupal\webform\WebformSubmissionInterface;

/**
 * Provides a 'table' trait.
 */
trait WebformTableTrait {

  /**
   * {@inheritdoc}
   */
  public function prepare(array &$element, WebformSubmissionInterface $webform_submission = NULL) {
    parent::prepare($element, $webform_submission);

    // Add missing element class.
    $element['#attributes']['class'][] = str_replace('_', '-', $element['#type']);

    // Add one column header is not #header is specified.
    if (!isset($element['#header'])) {
      $element['#header'] = [
        (isset($element['#title']) ? $element['#title'] : ''),
      ];
    }

    // Convert associative array of options into one column row.
    if (isset($element['#options'])) {
      foreach ($element['#options'] as $options_key => $options_value) {
        if (is_string($options_value)) {
          $element['#options'][$options_key] = [
            ['value' => $options_value],
          ];
        }
      }
    }

    $element['#attached']['library'][] = 'webform/webform.element.' . $element['#type'];

    // Set table select element's #process callback so that fix UX
    // and accessiblity issues.
    if ($this->getPluginId() === 'tableselect') {
      static::setProcessTableSelectCallback($element);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function setDefaultValue(array &$element) {
    if (isset($element['#default_value']) && is_array($element['#default_value'])) {
      $element['#default_value'] = array_combine($element['#default_value'], $element['#default_value']);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $form['options']['js_select'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Select all'),
      '#description' => $this->t('If checked, a select all checkbox will be added to the header.'),
      '#return_value' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  protected function getTableSelectElementSelectorOptions(array $element, $input_selector = '') {
    $title = $this->getAdminLabel($element) . ' [' . $this->getPluginLabel() . ']';
    $name = $element['#webform_key'];
    if ($this->hasMultipleValues($element)) {
      $selectors = [];
      foreach ($element['#options'] as $value => $text) {
        if (is_array($text)) {
          $text = $value;
        }
        $selectors[":input[name=\"{$name}[{$value}]$input_selector\"]"] = $text . ' [' . $this->t('Checkbox') . ']';
      }
      return [$title => $selectors];
    }
    else {
      return [":input[name=\"{$name}\"]" => $title];
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getElementSelectorSourceValues(array $element) {
    if ($this->hasMultipleValues($element)) {
      return [];
    }

    $name = $element['#webform_key'];
    $options = OptGroup::flattenOptions($element['#options']);
    return [":input[name=\"{$name}\"]" => $options];
  }

  /**
   * Process table select and attach JavaScript.
   *
   * @param array $element
   *   An associative array containing the properties and children of
   *   the tableselect element.
   *
   * @return array
   *   The processed element.
   *
   * @see \Drupal\Core\Render\Element\Tableselect::processTableselect
   */
  public static function processTableSelect(array $element) {
    $element['#attributes']['class'][] = 'webform-tableselect';
    $element['#attributes']['class'][] = 'js-webform-tableselect';
    $element['#attached']['library'][] = 'webform/webform.element.tableselect';
    return $element;
  }

  /**
   * Process table selected options and add #title to the table's options.
   *
   * @param array $element
   *   An associative array containing the properties and children of
   *   the tableselect element.
   *
   * @return array
   *   The processed element.
   *
   * @see \Drupal\Core\Render\Element\Tableselect::processTableselect
   */
  public static function processTableSelectOptions(array $element) {
    foreach ($element['#options'] as $key => $choice) {
      if (isset($element[$key]) && empty($element[$key]['#title'])) {
        if ($title = static::getTableSelectOptionTitle($choice)) {
          $element[$key]['#title'] = $title;
          $element[$key]['#title_display'] = 'invisible';
        }
      }
    }
    return $element;
  }

  /**
   * Set process table select element callbacks.
   *
   * @param array $element
   *   An associative array containing the properties and children of
   *   the table select element.
   *
   * @see \Drupal\Core\Render\Element\Tableselect::processTableselect
   */
  public static function setProcessTableSelectCallback(array &$element) {
    $class = get_called_class();
    $element['#process'] = [
      ['\Drupal\Core\Render\Element\Tableselect', 'processTableselect'],
      [$class , 'processTableSelect'],
      [$class , 'processTableSelectOptions'],
    ];
  }

  /**
   * Get table selection option title/text.
   *
   * Issue #2719453: Tableselect single radio button missing #title attribute
   * and is not accessible,
   *
   * @param array $option
   *   A table select option.
   *
   * @return string|\Drupal\Component\Render\MarkupInterface|null
   *   Table selection option title/text.
   *
   * @see https://www.drupal.org/project/drupal/issues/2719453
   */
  public static function getTableSelectOptionTitle(array $option) {
    if (is_array($option) && WebformArrayHelper::isAssociative($option)) {
      // Get first value from custom options.
      $title = reset($option);
      if (is_array($title)) {
        $title = \Drupal::service('renderer')->render($title);
      }
      return $title;
    }
    elseif (is_array($option) && !empty($option[0]['value'])) {
      // Get value from default options.
      // @see \Drupal\webform\Plugin\WebformElement\WebformTableTrait::prepare
      return $option[0]['value'];
    }
    else {
      return NULL;
    }
  }

}
