<?php

namespace Drupal\drupal_informea_api\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'legislation_relations' formatter.
 *
 * @FieldFormatter(
 *   id = "legislation_relations",
 *   label = @Translation("Legislation relations formatter"),
 *   field_types = {
 *     "boolean",
 *     "integer",
 *   }
 * )
 */
class LegislationRelationsFormatter extends FormatterBase {

  use SerializerObjectTrait;

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    return $this->serialize($this->getRelations($items));
  }

  /**
   * @param \Drupal\Core\Field\FieldItemListInterface $items
   *
   * @return array
   */
  public function getRelations(FieldItemListInterface $items) {
    /** @var \Drupal\node\Entity\Node $entity */
    $entity = $items->getEntity();
    $relations = [];

    $fields = [
      'field_amends' => 'amends',
      'field_amended_by' => 'amendedBy',
      'field_repeals' => 'repeals',
      'field_repealed_by' => 'repealedBy',
      'field_implements' => 'implements',
      'field_implemented_by' => 'implementedBy',
    ];

    $bundles = [
      'national_legislation' => 'legislation'
    ];

    foreach ($fields as $fieldName => $fieldRelation) {
      if ($entity->hasField($fieldName) && !$entity->get($fieldName)->isEmpty()) {
        /** @var \Drupal\Core\Entity\EntityInterface $referencedEntity */
        foreach ($entity->get($fieldName)->referencedEntities() as $referencedEntity) {
          $relations[] = [
            'to' => $referencedEntity->uuid(),
            'toTitle' => $referencedEntity->label(),
            'type' => !empty($bundles[$referencedEntity->bundle()]) ? $bundles[$referencedEntity->bundle()] : $referencedEntity->bundle(),
            'relation' => $fieldRelation,
            'startDate' => NULL,
            'endDate' => NULL,
          ];
        }
      }
    }
    return $relations;
  }

}
