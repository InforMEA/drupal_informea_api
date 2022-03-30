<?php

namespace Drupal\drupal_informea_api;

use Drupal\views\Render\ViewsRenderPipelineMarkup;

/**
 * Wrapper for passing serialized data through render arrays.
 *
 * @see \Drupal\drupal_informea_api\Normalizer\DataNormalizer
 */
class SerializedData {

  /**
   * The wrapped data.
   *
   * @var string
   */
  protected $data;

  /**
   * SerializedData constructor.
   *
   * @param mixed $data
   *   The wrapped data.
   */
  public function __construct($data) {
    if (!empty($data) && is_array($data) && !empty($data[0]) && $data[0] instanceof static) {
      $this->data = $data[0]->data;
      return;
    }
    $this->data = $data;
  }

  /**
   * Create a serialized data object.
   *
   * @param mixed $data
   *   The wrapped data.
   *
   * @return static
   */
  public static function create($data) {
    if ($data instanceof static) {
      return $data;
    }
    return new static($data);
  }

  /**
   * Convert renderable object to a string.
   *
   * This function needs to return a non-empty string in order to be processed
   * correctly by Drupal's rendering system.
   *
   * @return string
   *   A placeholder string representation.
   */
  public function __toString() {
    // This must not be empty.
    if ($this->data && $this->data instanceof ViewsRenderPipelineMarkup) {
      return $this->data->__toString();
    }
    return '[...]';
  }

  /**
   * Extract the wrapped data.
   *
   * @return mixed
   *   The wrapped data.
   */
  public function getData() {
    return $this->data;
  }

}
