<?php

namespace Drupal\drupal_informea_api\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemList;
use Drupal\taxonomy\Entity\Term;

/**
 * Plugin implementation of the 'informea_power_tags' formatter.
 *
 * @FieldFormatter(
 *   id = "informea_power_tags",
 *   label = @Translation("Informea power tags"),
 *   description = @Translation("Display power tags for informea export."),
 *   field_types = {
 *     "powertagging_tags"
 *   }
 * )
 */
class InformeaPowerTagsFormatter extends InformeaTermEntityReferenceFormatter {

  /**
   * @param \Drupal\Core\Field\FieldItemList $field
   *
   * @return array
   */
  public function getEntities(FieldItemList $field) {
    $value = [];
    foreach ($field as $item) {
      if (empty($item->target_id)) {
        continue;
      }

      $term = Term::load($item->target_id);
      if (empty($term)) {
        continue;
      }

      $value[] = $this->getEntity($term);
    }

    return $value;
  }

}
