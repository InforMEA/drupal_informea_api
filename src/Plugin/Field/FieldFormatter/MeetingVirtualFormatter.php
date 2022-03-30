<?php

namespace Drupal\drupal_informea_api\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemList;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'meeting_virtual' formatter.
 *
 * @FieldFormatter(
 *   id = "informea_meeting_virtual",
 *   label = @Translation("[InforMEA] Meeting virtual boolean"),
 *   field_types = {
 *     "string",
 *     "list_string",
 *   }
 * )
 */
class MeetingVirtualFormatter extends FormatterBase {

  use SerializerObjectTrait;

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    $options = parent::defaultSettings();

    $options['virtual_string'] = 'online';

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $form = parent::settingsForm($form, $form_state);

    $form['virtual_string'] = [
      '#type' => 'textfield',
      '#title' => $this->t('String for online'),
      '#description' => $this->t('This formatter compares the field value with a desired value and returns a boolean.
      Useful when the meeting location field is a string or a list (text) that always has the same value (e.g. "online") for virtual events.'),
      '#default_value' => $this->getSetting('virtual_string') ?? 'online',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    if ($this->getSetting('virtual_string')) {
      $summary[] = $this->t('Online string: @string', ['@string' => $this->getSetting('virtual_string')]);
    }

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    return $this->serialize($this->getMeetingVirtual($items));
  }
  /**
   * @param \Drupal\Core\Field\FieldItemList $field
   *
   * @return string|null
   */
  public function getMeetingVirtual(FieldItemList $field) {
    return $field->value === $this->configuration['virtual_string'];
  }

}
