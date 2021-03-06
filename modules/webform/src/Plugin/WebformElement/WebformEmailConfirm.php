<?php

namespace Drupal\webform\Plugin\WebformElement;

use Drupal\Core\Form\FormStateInterface;
use Drupal\webform\Element\WebformHtmlEditor;
use Drupal\webform\WebformSubmissionInterface;

/**
 * Provides a 'email_confirm' element.
 *
 * @WebformElement(
 *   id = "webform_email_confirm",
 *   label = @Translation("Email confirm"),
 *   description = @Translation("Provides a form element for double-input of email addresses."),
 *   category = @Translation("Advanced elements"),
 *   states_wrapper = TRUE,
 * )
 */
class WebformEmailConfirm extends Email {

  /**
   * {@inheritdoc}
   */
  public function getDefaultProperties() {
    $properties = parent::getDefaultProperties() + [
      // Email confirm settings.
      'confirm__title' => '',
      'confirm__description' => '',
      'confirm__placeholder' => '',
      // Wrapper.
      'wrapper_type' => 'fieldset',
    ];
    unset(
      $properties['multiple'],
      $properties['multiple__header_label']
    );
    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function prepare(array &$element, WebformSubmissionInterface $webform_submission = NULL) {
    parent::prepare($element, $webform_submission);
    if (isset($element['#confirm__description'])) {
      $element['#confirm__description'] = WebformHtmlEditor::checkMarkup($element['#confirm__description']);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);
    $form['email_confirm'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Email confirm settings'),
    ];
    $form['email_confirm']['confirm__title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Email confirm title'),
    ];
    $form['email_confirm']['confirm__description'] = [
      '#type' => 'webform_html_editor',
      '#title' => $this->t('Email confirm description'),
    ];
    $form['email_confirm']['confirm__placeholder'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Email confirm placeholder'),
    ];

    // Remove unsupported title and description display from composite elements.
    if ($this->isComposite()) {
      unset($form['form']['display_container']['title_display']['#options']['inline']);
      unset($form['form']['display_container']['description_display']['#options']['tooltip']);
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  protected function getElementSelectorInputsOptions(array $element) {
    return [
      'mail_1' => $this->getAdminLabel($element) . '1 [' . $this->t('Email') . ']',
      'mail_2' => $this->getAdminLabel($element) . ' 2 [' . $this->t('Email') . ']',
    ];
  }

}
