<?php

namespace Drupal\drupal_informea_api\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'visibility' formatter.
 *
 * @FieldFormatter(
 *   id = "informea_visibility",
 *   label = @Translation("[InforMEA] Visibility formatter"),
 *   field_types = {
 *     "string",
 *     "computed",
 *     "computed_string",
 *     "list_integer",
 *     "list_float",
 *     "list_string",
 *   }
 * )
 */
class VisibilityFormatter extends FormatterBase {

  use SerializerObjectTrait;

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    $options = parent::defaultSettings();

    $options['private_string'] = 'private';

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $form = parent::settingsForm($form, $form_state);

    $form['private_string'] = [
      '#type' => 'textfield',
      '#title' => $this->t('String for private'),
      '#description' => $this->t('This formatter compares the field value with a desired value and returns either "public" or "private".
      Useful when the privacy field is a String or a List (text) field always containing the same string (e.g. "private")'),
      '#default_value' => $this->getSetting('private_string') ?? 'private',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    if ($this->getSetting('private_string')) {
      $summary[] = $this->t('Private string: @string', ['@string' => $this->getSetting('private_string')]);
    }

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $access = 'public';
    if ($items->value === $this->configuration['private_string']) {
      $access = NULL;
    }

    return $this->serialize($access);
  }

}
