<?php

namespace Drupal\drupal_informea_api\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface;

/**
 * Plugin implementation of the 'decision_published' formatter.
 *
 * @FieldFormatter(
 *   id = "informea_publication_date",
 *   label = @Translation("[InforMEA] Publication date"),
 *   field_types = {
 *     "daterange",
 *     "flexible_daterange",
 *   }
 * )
 */
class DecisionPublishedFormatter extends FormatterBase {

  use SerializerObjectTrait;

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    return $this->serialize($this->getDate($items));
  }

  protected function getDate(FieldItemListInterface $items) {
    if ($items->isEmpty()) {
      return NULL;
    }

    if (!empty($items->end_value)) {
      return date('Y-m-d', strtotime($items->end_value));
    }

    if (!empty($items->value)) {
      return date('Y-m-d', strtotime($items->value));
    }

    return NULL;
  }

}
