<?php

namespace Drupal\drupal_informea_api\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'treaty' formatter.
 *
 * @FieldFormatter(
 *   id = "informea_treaty",
 *   label = @Translation("[Informea] Treaty (use on entity ID field)"),
 *   field_types = {
 *     "integer",
 *     "string",
 *     "entity_reference",
 *   }
 * )
 */
class TreatyFormatter extends FormatterBase {

  use SerializerObjectTrait;


  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    $options = parent::defaultSettings();

    $options['treaties'] = '';

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $form = parent::settingsForm($form, $form_state);

    $form['treaties'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Treaty IDs'),
      '#description' => $this->t('Separate each treaty with a comma (,)'),
      '#default_value' => $this->getSetting('treaties') ?? '',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    if ($this->getSetting('treaties')) {
      $summary[] = $this->t('Treaty: @string', ['@string' => $this->getSetting('treaties')]);
    }

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $treaties = $this->getTreaties($items);
    return $this->serialize(reset($treaties));
  }

  protected function getTreaties(FieldItemListInterface $items) {
    $treaties = $this->configuration['treaties'];
    $treaties = explode(',', $treaties);
    foreach ($treaties as &$treaty) {
      $treaty = trim($treaty);
    }
    return $treaties;
  }

}
