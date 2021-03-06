<?php

namespace Drupal\webform\Plugin\WebformElement;

use Drupal\webform\Plugin\WebformElementEntityReferenceInterface;

/**
 * Provides a 'webform_entity_checkboxes' element.
 *
 * @WebformElement(
 *   id = "webform_entity_checkboxes",
 *   label = @Translation("Entity checkboxes"),
 *   description = @Translation("Provides a form element to select multiple entity references using checkboxes."),
 *   category = @Translation("Entity reference elements"),
 * )
 */
class WebformEntityCheckboxes extends Checkboxes implements WebformElementEntityReferenceInterface {

  use WebformEntityReferenceTrait;
  use WebformEntityOptionsTrait;

}
