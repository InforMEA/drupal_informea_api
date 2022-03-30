<?php

namespace Drupal\drupal_informea_api\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\link\Plugin\Field\FieldFormatter\LinkSeparateFormatter;

/**
 * Plugin implementation of the 'link' formatter.
 *
 * @FieldFormatter(
 *   id = "informea_link_with_text",
 *   label = @Translation("[InforMEA] Link with text"),
 *   field_types = {
 *     "link"
 *   }
 * )
 */
class LinkWithTextFormatter extends LinkSeparateFormatter {

  use SerializerObjectTrait;

  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = parent::viewElements($items, $langcode);

    if (empty($elements)) {
      return [];
    }

    $results = [];
    foreach ($items as $item) {
      $results[] = [
        'uri' => $item->get('uri')->getValue(),
        'title' => $item->get('title')->getValue(),
      ];
    }
    return $this->serialize($results);
  }
}
