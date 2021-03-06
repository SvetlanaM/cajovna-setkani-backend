<?php

namespace Drupal\webform\Element;

use Drupal\Core\Render\Element\FormElement;
use Drupal\Core\Form\FormStateInterface;
use Drupal\webform\Utility\WebformElementHelper;

/**
 * Provides a webform element requiring users to double-element and confirm an email address.
 *
 * Formats as a pair of email addresses fields, which do not validate unless
 * the two entered email addresses match.
 *
 * @FormElement("webform_email_confirm")
 */
class WebformEmailConfirm extends FormElement {

  use WebformCompositeFormElementTrait;

  /**
   * {@inheritdoc}
   */
  public function getInfo() {
    $class = get_class($this);
    return [
      '#input' => TRUE,
      '#size' => 60,
      '#process' => [
        [$class, 'processWebformEmailConfirm'],
      ],
      '#pre_render' => [
        [$class, 'preRenderWebformCompositeFormElement'],
      ],
      '#required' => FALSE,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function valueCallback(&$element, $input, FormStateInterface $form_state) {
    if ($input === FALSE) {
      if (!isset($element['#default_value'])) {
        $element['#default_value'] = '';
      }
      return [
        'mail_1' => $element['#default_value'],
        'mail_2' => $element['#default_value'],
      ];
    }
    else {
      return $input;
    }
  }

  /**
   * Expand an email confirm field into two HTML5 email elements.
   */
  public static function processWebformEmailConfirm(&$element, FormStateInterface $form_state, &$complete_form) {
    $element['#tree'] = TRUE;

    // Get shared properties.
    $shared_properties = [
      '#title_display',
      '#description_display',
      '#size',
      '#maxlength',
      '#pattern',
      '#pattern_error',
      '#required',
      '#required_error',
      '#placeholder',
      '#attributes',
    ];
    $element_shared_properties = ['#type' => 'email'] + array_intersect_key($element, array_combine($shared_properties, $shared_properties));

    // Get mail 1 email element.
    $mail_1_properties = [
      '#title',
      '#description',
    ];
    $element['mail_1'] = $element_shared_properties + array_intersect_key($element, array_combine($mail_1_properties, $mail_1_properties));
    $element['mail_1']['#attributes']['class'][] = 'webform-email';
    $element['mail_1']['#value'] = empty($element['#value']) ? NULL : $element['#value']['mail_1'];
    $element['mail_1']['#error_no_message'] = TRUE;

    // Build mail_2 confirm email element.
    $element['mail_2'] = $element_shared_properties;
    $element['mail_2']['#title'] = t('Confirm email');
    foreach ($element as $key => $value) {
      if (strpos($key, '#confirm__') === 0) {
        $element['mail_2'][str_replace('#confirm__', '#', $key)] = $value;
      }
    }
    $element['mail_2']['#attributes']['class'][] = 'webform-email-confirm';
    $element['mail_2']['#value'] = empty($element['#value']) ? NULL : $element['#value']['mail_2'];
    $element['mail_2']['#error_no_message'] = TRUE;

    // Don't require the main element.
    $element['#required'] = FALSE;

    // Hide title and description from being display.
    $element['#title_display'] = 'invisible';
    $element['#description_display'] = 'invisible';

    // Remove properties that are being applied to the sub elements.
    unset($element['#maxlength']);
    unset($element['#attributes']);
    unset($element['#description']);

    // Add validate callback.
    $element += ['#element_validate' => []];
    array_unshift($element['#element_validate'], [get_called_class(), 'validateWebformEmailConfirm']);

    return $element;
  }

  /**
   * Validates an email confirm element.
   */
  public static function validateWebformEmailConfirm(&$element, FormStateInterface $form_state, &$complete_form) {
    $mail_1 = trim($element['mail_1']['#value']);
    $mail_2 = trim($element['mail_2']['#value']);
    $has_access = (!isset($element['#access']) || $element['#access'] === TRUE);
    if ($has_access) {
      if ((!empty($mail_1) || !empty($mail_2)) && strcmp($mail_1, $mail_2)) {
        $form_state->setError($element, t('The specified email addresses do not match.'));
      }
      else {
        // NOTE: Only mail_1 needs to be validated since mail_2 is the same value.
        // Verify the required value.
        if ($element['mail_1']['#required'] && empty($mail_1)) {
          $required_error_title = (isset($element['mail_1']['#title'])) ? $element['mail_1']['#title'] : NULL;
          WebformElementHelper::setRequiredError($element, $form_state, $required_error_title);
        }
        // Verify that the value is not longer than #maxlength.
        if (isset($element['mail_1']['#maxlength']) && mb_strlen($mail_1) > $element['mail_1']['#maxlength']) {
          $t_args = [
            '@name' => $element['mail_1']['#title'],
            '%max' => $element['mail_1']['#maxlength'],
            '%length' => mb_strlen($mail_1),
          ];
          $form_state->setError($element, t('@name cannot be longer than %max characters but is currently %length characters long.', $t_args));
        }
      }
    }

    // Set #title for other validation callbacks.
    // @see \Drupal\webform\Plugin\WebformElementBase::validateUnique
    if (isset($element['mail_1']['#title'])) {
      $element['#title'] = $element['mail_1']['#title'];
    }

    // Email field must be converted from a two-element array into a single
    // string regardless of validation results.
    $form_state->setValueForElement($element['mail_1'], NULL);
    $form_state->setValueForElement($element['mail_2'], NULL);

    $element['#value'] = $mail_1;
    $form_state->setValueForElement($element, $mail_1);
  }

}
