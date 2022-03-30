<?php

namespace Drupal\drupal_informea_api\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemList;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\file\FileInterface;

/**
 * Plugin implementation of the 'informea_image' formatter.
 *
 * @FieldFormatter(
 *   id = "informea_image_url",
 *   label = @Translation("[InforMEA] Image URL"),
 *   field_types = {
 *     "image",
 *   }
 * )
 */
class InformeaImageFormatter extends FormatterBase {

  use SerializerObjectTrait;

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    return $this->serialize($this->getImageUrl($items));
  }

  /**
   * @param \Drupal\Core\Field\FieldItemList $field
   *
   * @return string|null
   * @throws \Drupal\Core\TypedData\Exception\MissingDataException
   */
  public function getImageUrl(FieldItemList $field) {
    if ($field->isEmpty()) {
      return NULL;
    }

    /** @var \Drupal\file\FileInterface $file */
    $file = $field->first()->entity ?? NULL;
    if (!$file instanceof FileInterface) {
      return NULL;
    }

    return $file->createFileUrl(FALSE);
  }

}
