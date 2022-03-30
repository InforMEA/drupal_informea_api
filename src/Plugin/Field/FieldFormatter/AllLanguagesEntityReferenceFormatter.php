<?php

namespace Drupal\drupal_informea_api\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\facets\Plugin\facets\hierarchy\Taxonomy;
use Drupal\node\NodeInterface;
use Drupal\taxonomy\TermInterface;

/**
 * Plugin implementation of the 'all_languages_summary' formatter.
 *
 * @FieldFormatter(
 *   id = "all_languages_entity_reference",
 *   label = @Translation("Entity Reference in all languages"),
 *   field_types = {
 *     "entity_reference",
 *   }
 * )
 */
class AllLanguagesEntityReferenceFormatter extends FormatterBase implements ContainerFactoryPluginInterface {

  use SerializerObjectTrait;

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $languages = \Drupal::languageManager()->getLanguages();
    $defaultLanguageId = \Drupal::languageManager()->getDefaultLanguage()->getId();

    /** @var \Drupal\Core\Entity\TranslatableInterface $entity */
    $entity = $items->getEntity();

    $summaries = [];
    if (!$entity->isTranslatable() || !$items->getFieldDefinition()->isTranslatable()) {
      if (!$items->isEmpty()) {
        $summaries = [$defaultLanguageId => $items->summary];
      }
    }

    $fieldName = $this->fieldDefinition->getName();
    foreach ($languages as $languageId => $language) {
      if (!$entity->isTranslatable()) {
        continue;
      }

      if (!$entity->hasTranslation($languageId)) {
        continue;
      }

      $translation = $entity->getTranslation($languageId);
      $referencedEntities = $translation->get($fieldName)->referencedEntities();
      if (empty($entity)) {
        continue;
      }

      foreach ($referencedEntities as $referencedEntity) {
        if ($referencedEntity instanceof TermInterface) {
          $summaries[$languageId] = $referencedEntity->getName();
        }
        if ($referencedEntity instanceof NodeInterface) {
          $summaries[$languageId] = $referencedEntity->getTitle();
        }
      }
    }

    return $this->serialize($summaries);
  }

}
