<?php

namespace Drupal\webform\Plugin\WebformElement;

use Drupal\Core\Form\FormStateInterface;
use Drupal\webform\Plugin\WebformElementBase;
use Drupal\webform\WebformInterface;
use Drupal\webform\WebformSubmissionInterface;

/**
 * Provides a base 'numeric' class.
 */
abstract class NumericBase extends WebformElementBase {

  /**
   * {@inheritdoc}
   */
  public function getDefaultProperties() {
    return [
      // Form validation.
      'readonly' => FALSE,
      'size' => '',
      'minlength' => '',
      'maxlength' => '',
      'placeholder' => '',
      'autocomplete' => 'on',
    ] + parent::getDefaultProperties();
  }

  /**
   * {@inheritdoc}
   */
  public function prepare(array &$element, WebformSubmissionInterface $webform_submission = NULL) {
    parent::prepare($element, $webform_submission);
    if ($this->hasProperty('step') && !isset($element['#step'])) {
      $element['#step'] = $this->getDefaultProperty('step') ?: 'any';
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getTestValues(array $element, WebformInterface $webform, array $options = []) {
    $element += ['#min' => 1, '#max' => 10];
    return [
      $element['#min'],
      floor((($element['#max'] - $element['#min']) / 2) + $element['#min']),
      $element['#max'],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $form['number'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Number settings'),
    ];
    $form['number']['number_container'] = $this->getFormInlineContainer();
    $form['number']['number_container']['min'] = [
      '#type' => 'number',
      '#title' => $this->t('Minimum'),
      '#description' => $this->t('Specifies the minimum value.'),
      '#step' => 'any',
      '#size' => 4,
    ];
    $form['number']['number_container']['max'] = [
      '#type' => 'number',
      '#title' => $this->t('Maximum'),
      '#description' => $this->t('Specifies the maximum value.'),
      '#step' => 'any',
      '#size' => 4,
    ];
    $form['number']['number_container']['step'] = [
      '#type' => 'number',
      '#title' => $this->t('Steps'),
      '#description' => $this->t('Specifies the legal number intervals. Leave blank to support any number interval. Decimals are supported.'),
      '#step' => 'any',
      '#size' => 4,
    ];
    return $form;
  }

}
