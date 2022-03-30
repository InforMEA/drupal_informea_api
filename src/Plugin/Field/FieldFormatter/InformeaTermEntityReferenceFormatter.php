<?php

namespace Drupal\drupal_informea_api\Plugin\Field\FieldFormatter;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Field\FieldItemList;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'informea_term_entity_reference' formatter.
 *
 * @FieldFormatter(
 *   id = "informea_term_entity_reference",
 *   label = @Translation("Informea term entity reference"),
 *   description = @Translation("Display term references for informea export."),
 *   field_types = {
 *     "entity_reference"
 *   }
 * )
 */
class InformeaTermEntityReferenceFormatter extends FormatterBase {

  use SerializerObjectTrait;

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    return $this->serialize($this->getEntities($items));
  }

  /**
   * @param \Drupal\Core\Field\FieldItemList $field
   *
   * @return array
   */
  public function getEntities(FieldItemList $field) {
    $value = [];
    foreach ($field as $item) {
      $value[] = $this->getEntity($item->entity);
    }

    return $value;
  }

  protected function getEntity(EntityInterface $entity) {
    return [
      'term' => $entity->label(),
      'uri' => $entity->toUrl()->setAbsolute(TRUE)->toString(TRUE)->getGeneratedUrl(),
    ];
  }

}
