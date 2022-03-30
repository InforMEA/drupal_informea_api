<?php

namespace Drupal\drupal_informea_api\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'null' formatter.
 * Not a real formatter, only use to display null for required fields that do
 * not exist in system
 *
 * @FieldFormatter(
 *   id = "informea_null_formatter",
 *   label = @Translation("[InforMEA] Null formatter"),
 *   field_types = {
 *     "boolean",
 *     "integer",
 *     "decimal",
 *     "float",
 *     "string",
 *     "computed",
 *     "computed_string",
 *     "text",
 *     "text_long",
 *     "text_with_summary",
 *   }
 * )
 */
class NullFormatter extends FormatterBase {

  use SerializerObjectTrait;

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    return $this->serialize(NULL);
  }

}
