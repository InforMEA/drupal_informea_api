<?php

namespace Drupal\drupal_informea_api\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemList;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'multiple_value_list_text' formatter.
 *
 * @FieldFormatter(
 *   id = "informea_multiple_value_list_text",
 *   label = @Translation("[InforMEA] Multiple value List (text) labels"),
 *   field_types = {
 *     "integer",
 *     "list_integer",
 *     "list_float",
 *     "list_string",
 *   }
 * )
 */
class MultipleValueListTextFormatter extends FormatterBase {

  use SerializerObjectTrait;

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    return $this->serialize($this->getFieldValues($items));
  }

  /**
   * @param \Drupal\Core\Field\FieldItemList $field
   *
   * @return array
   */
  public function getFieldValues(FieldItemList $field) {
    if ($field->isEmpty()) {
      return NULL;
    }
    $values = array_column($field->getValue(), 'value');
    $allowed_values = $field->getSetting('allowed_values');
    $value_labels = [];
    foreach ($values as $value) {
      $value_labels[] = $allowed_values[$value];
    }
    return $value_labels;
  }

}
