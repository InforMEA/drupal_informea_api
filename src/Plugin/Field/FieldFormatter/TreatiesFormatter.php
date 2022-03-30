<?php

namespace Drupal\drupal_informea_api\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\drupal_informea_api\SerializedData;

/**
 * Plugin implementation of the 'treaty' formatter.
 *
 * @FieldFormatter(
 *   id = "informea_treaties",
 *   label = @Translation("[InforMEA] Treaties (use on entity ID field)"),
 *   field_types = {
 *     "integer",
 *     "string",
 *     "entity_reference",
 *   }
 * )
 */
class TreatiesFormatter extends TreatyFormatter {

  use SerializerObjectTrait;

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    return $this->serialize($this->getTreaties($items));

  }

}
