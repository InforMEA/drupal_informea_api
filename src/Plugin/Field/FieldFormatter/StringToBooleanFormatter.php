<?php

namespace Drupal\drupal_informea_api\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemList;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'string_to_boolean_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "string_to_boolean_formatter",
 *   label = @Translation("String to boolean formatter"),
 *   field_types = {
 *     "string",
 *     "list_string",
 *   }
 * )
 */
class StringToBooleanFormatter extends FormatterBase {

  use SerializerObjectTrait;

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    return $this->serialize($this->getValue($items));
  }

  /**
   * @param \Drupal\Core\Field\FieldItemList $field
   *
   * @return boolean
   */
  public function getValue(FieldItemList $field) {
    $mapping = [
      'yes' => TRUE,
      'no' => FALSE,
    ];

    $fieldValue = strtolower($field->value);
    $value = isset($mapping[$fieldValue]) ? $mapping[$fieldValue] : NULL;

    return $value;
  }

}
