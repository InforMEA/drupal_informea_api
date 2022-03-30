<?php

namespace Drupal\drupal_informea_api\Plugin\Field\FieldFormatter;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Field\FieldItemList;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\file\Entity\File;
use Drupal\file\FileInterface;

/**
 * Plugin implementation of the 'informea_files' formatter.
 *
 * @FieldFormatter(
 *   id = "informea_files",
 *   label = @Translation("[InforMEA] Files field"),
 *   field_types = {
 *     "file",
 *   }
 * )
 */
class InformeaFilesFormatter extends FormatterBase {

  use SerializerObjectTrait;

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    return $this->serialize($this->getFiles($items));
  }

  /**
   * @param \Drupal\Core\Field\FieldItemList $field
   *
   * @return array
   */
  public function getFiles(FieldItemList $field) {
    $parent = $field->getEntity();
    $value = [];
    $languages = \Drupal::languageManager()->getLanguages();
    foreach ($languages as $languageId => $language) {
      if (!$parent->hasTranslation($languageId)) {
        continue;
      }

      $translation = $parent->getTranslation($languageId);
      foreach ($translation->get($field->getName()) as $item) {
        if (!$item->entity instanceof FileInterface) {
          continue;
        }
        $value[] = $this->getFileObjectFromFileEntity($item->entity, $translation);
      }
    }

    return $value;
  }

  protected function getFileObjectFromFileEntity(File $file, EntityInterface $parent) {
    $url = file_create_url($file->getFileUri());
    return [
      'id' => $file->id(),
      'url' => $url,
      'content' => NULL,
      'mimeType' => pathinfo($url, PATHINFO_EXTENSION),
      'language' => $parent->get('langcode')->value,
      'filename' => $file->label(),
    ];
  }

}
