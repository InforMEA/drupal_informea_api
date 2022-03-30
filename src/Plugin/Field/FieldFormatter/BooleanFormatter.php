<?php

namespace Drupal\drupal_informea_api\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemList;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'boolean_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "informea_boolean",
 *   label = @Translation("[InforMEA] Boolean field"),
 *   field_types = {
 *     "boolean",
 *   }
 * )
 */
class BooleanFormatter extends FormatterBase {

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
    return (boolean) $field->value;
  }

}
