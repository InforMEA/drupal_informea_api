<?php

namespace Drupal\drupal_informea_api\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemList;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'meeting_type' formatter.
 *
 * @FieldFormatter(
 *   id = "informea_meeting_type",
 *   label = @Translation("[InforMEA] Meeting type"),
 *   field_types = {
 *     "integer",
 *     "list_integer",
 *     "list_float",
 *     "list_string",
 *   }
 * )
 */
class MeetingTypeFormatter extends FormatterBase {

  use SerializerObjectTrait;

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    return $this->serialize($this->getMeetingType($items));
  }

  /**
   * @param \Drupal\Core\Field\FieldItemList $field
   *
   * @return string|null
   */
  public function getMeetingType(FieldItemList $field) {
    $value = array_column($field->getValue(), 'value');
    $value = reset($value);
    return $value ?: NULL;
  }

}
