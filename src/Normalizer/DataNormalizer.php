<?php

namespace Drupal\drupal_informea_api\Normalizer;

use Drupal\drupal_informea_api\SerializedData;
use Drupal\serialization\Normalizer\NormalizerBase;

/**
 * Unwrap a SerializedData object and normalize the data inside.
 *
 * @see \Drupal\drupal_informea_api\SerializedData
 */
class DataNormalizer extends NormalizerBase {

  /**
   * The interface or class that this Normalizer supports.
   *
   * @var array
   */
  protected $supportedInterfaceOrClass = [SerializedData::class];

  /**
   * {@inheritdoc}
   */
  public function normalize($object, $format = NULL, array $context = []) {
    /** @var \Drupal\drupal_informea_api\SerializedData $object */
    /** @var \Symfony\Component\Serializer\Normalizer\NormalizerInterface $normalizer */
    $normalizer = $this->serializer;
    return $normalizer->normalize($object->getData());
  }

}
