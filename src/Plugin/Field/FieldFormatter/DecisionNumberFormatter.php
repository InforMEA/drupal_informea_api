<?php

namespace Drupal\drupal_informea_api\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemList;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'decision_number' formatter.
 *
 * @FieldFormatter(
 *   id = "informea_decision_number",
 *   label = @Translation("[InforMEA] Decision number"),
 *   field_types = {
 *     "string",
 *   }
 * )
 */
class DecisionNumberFormatter extends FormatterBase {

  use SerializerObjectTrait;

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    return $this->serialize($this->getDecisionNumber($items));
  }
  /**
   * @param \Drupal\Core\Field\FieldItemList $field
   *
   * @return string|null
   */
  public function getDecisionNumber(FieldItemList $field) {
    return trim(strtoupper(str_replace('decision', '', strtolower($field->value))));
  }

}
