<?php

namespace Drupal\drupal_informea_api\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemList;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface;

/**
 * Plugin implementation of the 'meeting_image_copyright' formatter.
 *
 * @FieldFormatter(
 *   id = "informea_image_alt",
 *   label = @Translation("[InforMEA] Image alt (copyright info)"),
 *   field_types = {
 *     "image",
 *   }
 * )
 */
class MeetingImageCopyrightFormatter extends FormatterBase {

  use SerializerObjectTrait;

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    return $this->serialize($this->getMeetingImageCopyright($items));
  }

  /**
   * @param \Drupal\Core\Field\FieldItemList $field
   *
   * @return string|null
   * @throws \Drupal\Core\TypedData\Exception\MissingDataException
   */
  public function getMeetingImageCopyright(FieldItemList $field) {
    if ($field->isEmpty()) {
      return NULL;
    }

    return !empty($field->first()->getValue()['alt']) ?
      $field->first()->getValue()['alt'] :
      NULL;
  }

}
