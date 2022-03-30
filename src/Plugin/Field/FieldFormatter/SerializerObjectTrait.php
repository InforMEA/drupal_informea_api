<?php

namespace Drupal\drupal_informea_api\Plugin\Field\FieldFormatter;

use Drupal\drupal_informea_api\SerializedData;

trait SerializerObjectTrait {

  protected function serialize($object) {
    return [
      [
        '#type' => 'data',
        '#data' => SerializedData::create($object),
      ],
    ];
  }

}
