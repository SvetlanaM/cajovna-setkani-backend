<?php

namespace Drupal\metatag_pinterest\Plugin\metatag\Tag;

use Drupal\metatag\Plugin\metatag\Tag\MetaNameBase;

/**
 * The Pinterest "nohover" meta tag.
 *
 * @MetatagTag(
 *   id = "pinterest_nohover",
 *   label = @Translation("No hover"),
 *   description = @Translation("Do not show hovering Save or Search buttons, generated by the Pinterest browser extensions."),
 *   name = "pinterest",
 *   group = "pinterest",
 *   weight = 2,
 *   type = "string",
 *   secure = FALSE,
 *   multiple = FALSE
 * )
 */
class PinterestNohover extends MetaNameBase {

  /**
   * {@inheritdoc}
   */
  public function form(array $element = []) {
    $form = [
      '#type' => 'checkbox',
      '#title' => $this->label(),
      '#description' => $this->description(),
      '#default_value' => ($this->value === 'nohover') ?: '',
      '#required' => isset($element['#required']) ? $element['#required'] : FALSE,
      '#element_validate' => [[get_class($this), 'validateTag']],
      '#return_value' => 'nohover',
    ];

    return $form;
  }

}
