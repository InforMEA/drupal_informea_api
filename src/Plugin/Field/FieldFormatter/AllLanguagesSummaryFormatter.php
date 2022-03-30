<?php

namespace Drupal\drupal_informea_api\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

/**
 * Plugin implementation of the 'all_languages_summary' formatter.
 *
 * @FieldFormatter(
 *   id = "informea_all_languages_summary",
 *   label = @Translation("[InforMEA] Field summary in all languages"),
 *   field_types = {
 *     "text_with_summary",
 *   }
 * )
 */
class AllLanguagesSummaryFormatter extends FormatterBase implements ContainerFactoryPluginInterface {

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
      $summary = $translation->get($fieldName)->summary;
      if (empty($summary)) {
        continue;
      }

      $summaries[$languageId] = $summary;
    }

    return $this->serialize($summaries);
  }

}
