<?php

namespace Drupal\drupal_informea_api\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemList;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface;

/**
 * Plugin implementation of the 'meeting_status' formatter.
 *
 * @FieldFormatter(
 *   id = "informea_meeting_status",
 *   label = @Translation("[InforMEA] Meeting status (use on entity ID field)"),
 *   field_types = {
 *     "integer",
 *     "entity_reference",
 *   }
 * )
 */
class MeetingStatusFormatter extends FormatterBase {

  use SerializerObjectTrait;

  const MEETING_STATUS_TENTATIVE = 'tentative';
  const MEETING_STATUS_CONFIRMED = 'confirmed';
  const MEETING_STATUS_POSTPONED = 'postponed';
  const MEETING_STATUS_CANCELLED = 'cancelled';
  const MEETING_STATUS_NODATE = 'nodate';
  const MEETING_STATUS_OVER = 'over';

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    $options = parent::defaultSettings();

    $options['field_start_date'] = '';
    $options['field_end_date'] = '';
    $options['field_date_range'] = '';

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $form = parent::settingsForm($form, $form_state);

    $form['field_start_date'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Start date field'),
      '#description' => $this->t('Only use this if start date and end date are 2 separate fields.'),
      '#default_value' => $this->getSetting('field_start_date'),
    ];

    $form['field_end_date'] = [
      '#type' => 'textfield',
      '#title' => $this->t('End date field'),
      '#default_value' => $this->getSetting('field_end_date'),
      '#description' => $this->t('Only use this if start date and end date are 2 separate fields.'),
    ];

    $form['field_date_range'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Date range field (containing both start/end dates)'),
      '#default_value' => $this->getSetting('field_date_range'),
      '#description' => $this->t('Only use this if start date and end date are in the same daterange field.'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    if ($this->getSetting('field_start_date')) {
      $summary[] = $this->t('Start date field @field', ['@field' => $this->getSetting('field_start_date')]);
    }
    if ($this->getSetting('field_end_date')) {
      $summary[] = $this->t('End date field @field', ['@field' => $this->getSetting('field_end_date')]);
    }
    if ($this->getSetting('field_date_range')) {
      $summary[] = $this->t('Date range field @field', ['@field' => $this->getSetting('field_date_range')]);
    }

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    return $this->serialize($this->getMeetingStatus($items));
  }
  /**
   * @param \Drupal\Core\Field\FieldItemList $field
   *
   * @return string|null
   */
  public function getMeetingStatus(FieldItemList $field) {
    $parent = $field->getEntity();

    $start = NULL;
    $end = NULL;

    $field_start_date = $this->configuration['field_start_date'] ?? '';
    $field_end_date = $this->configuration['field_end_date'] ?? '';
    $field_date_range = $this->configuration['field_date_range'] ?? '';

    if (!empty($field_start_date) && $parent->hasField($field_start_date) && !$parent->get($field_start_date)->isEmpty()) {
      $start = $parent->get($field_start_date);
    }

    if (!empty($field_end_date) && $parent->hasField($field_end_date) && !$parent->get($field_end_date)->isEmpty()) {
      $end = $parent->get($field_end_date);
    }

    if (!empty($field_date_range) && $parent->hasField($field_date_range) && !$parent->get($field_date_range)->isEmpty()) {
      $start = $parent->get($field_date_range)->start_date;
      $end = $parent->get($field_date_range)->end_date;
    }

    if (empty($start) || empty($end)) {
      return static::MEETING_STATUS_NODATE;
    }

    $endTime =  \Drupal::service('date.formatter')->format($end->getTimestamp(), 'custom', DateTimeItemInterface::DATETIME_STORAGE_FORMAT);
    $endTime = strtotime($endTime);
    if ($endTime < time()) {
      return static::MEETING_STATUS_OVER;
    }

    return static::MEETING_STATUS_CONFIRMED;
  }

}
