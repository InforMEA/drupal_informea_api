<?php

namespace Drupal\drupal_informea_api\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

/**
 * Plugin implementation of the 'all_languages_string' formatter.
 *
 * @FieldFormatter(
 *   id = "informea_all_languages_string",
 *   label = @Translation("[InforMEA] String field in all languages"),
 *   field_types = {
 *     "string",
 *     "string_long",
 *     "computed",
 *     "computed_string",
 *     "text",
 *     "text_long",
 *     "text_with_summary",
 *   }
 * )
 */
class AllLanguagesStringFormatter extends FormatterBase implements ContainerFactoryPluginInterface {

  use SerializerObjectTrait;

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $languages = \Drupal::languageManager()->getLanguages();
    $defaultLanguageId = \Drupal::languageManager()->getDefaultLanguage()->getId();

    /** @var \Drupal\Core\Entity\TranslatableInterface $entity */
    $entity = $items->getEntity();

    $values = [];
    if (!$entity->isTranslatable() || !$items->getFieldDefinition()->isTranslatable()) {
      if (!$items->isEmpty()) {
        $values = [$defaultLanguageId => $items->value];
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
      $value = $translation->get($fieldName)->value;
      if (empty($value)) {
        continue;
      }

      $values[$languageId] = $value;
    }

    return $this->serialize($values);
  }

}
