<?php

namespace Drupal\drupal_informea_api\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\Plugin\Field\FieldFormatter\EntityReferenceFormatterBase;
use Drupal\taxonomy\Entity\Term;

/**
 * Plugin implementation of the 'informea_country_iso3' formatter.
 *
 * @FieldFormatter(
 *   id = "informea_country_iso3",
 *   label = @Translation("[InforMEA] Country ISO3"),
 *   field_types = {
 *     "entity_reference",
 *   }
 * )
 */
class InformeaCountryIso3Formatter extends EntityReferenceFormatterBase {

  use SerializerObjectTrait;

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    $options = parent::defaultSettings();

    $options['field_iso3'] = '';

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $form = parent::settingsForm($form, $form_state);

    $form['field_iso3'] = [
      '#type' => 'textfield',
      '#title' => $this->t('ISO3 field name'),
      '#default_value' => $this->getSetting('field_iso3') ?? '',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    if ($this->getSetting('field_iso3')) {
      $summary[] = $this->t('ISO3 field: @string', ['@string' => $this->getSetting('field_iso3')]);
    }

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    return $this->serialize($this->getIso3($items, $langcode));
  }

  /**
   * @param \Drupal\Core\Field\FieldItemListInterface $items
   *
   * @return array
   */
  public function getIso3(FieldItemListInterface $items, $langcode) {
    if (empty($items->getValue())) {
      return [];
    }
    $results = [];
    $field = $this->configuration['field_iso3'] ?? '';

    if (empty($field)) {
      return $results;
    }

    foreach ($items->referencedEntities() as $item) {
      if ($item->hasField($field)) {
        $results[] = $item->get($field)->value;
      }
    }

    return $results;
  }

}
