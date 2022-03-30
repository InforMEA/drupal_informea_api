<?php

namespace Drupal\drupal_informea_api\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'draggableviews_weight' formatter.
 *
 * @FieldFormatter(
 *   id = "informea_draggableviews_weight",
 *   label = @Translation("[InforMEA] Draggableviews weight"),
 *   field_types = {
 *     "boolean",
 *     "integer",
 *     "decimal",
 *     "float",
 *     "string",
 *     "computed",
 *     "computed_string",
 *     "text",
 *     "text_long",
 *     "text_with_summary",
 *   }
 * )
 */
class DraggableViewsWeightFormatter extends FormatterBase {

  use SerializerObjectTrait;

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    $options = parent::defaultSettings();

    $options['view_name'] = '';
    $options['view_display'] = '';

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $form = parent::settingsForm($form, $form_state);

    $form['view_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('View ID'),
      '#default_value' => $this->getSetting('view_name'),
    ];

    $form['view_display'] = [
      '#type' => 'textfield',
      '#title' => $this->t('View display ID'),
      '#default_value' => $this->getSetting('view_display'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    if ($this->getSetting('view_name')) {
      $summary[] = $this->t('View ID @view_name', ['@view_name' => $this->getSetting('view_name')]);
    }
    if ($this->getSetting('view_display')) {
      $summary[] = $this->t('View display @view_display', ['@view_display' => $this->getSetting('view_display')]);
    }

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    return $this->serialize($this->getDraggableviewsValue($items));
  }

  protected function getDraggableviewsValue(FieldItemListInterface $items) {
    /** @var \Drupal\Core\Entity\EntityInterface $entity */
    $entity = $items->getParent()->getEntity();
    $id = $entity->id();

    $weight = \Drupal::database()->select('draggableviews_structure', 'd')
      ->condition('view_name', $this->getSetting('view_name'))
      ->condition('view_display', $this->getSetting('view_display'))
      ->condition('entity_id', $id)
      ->fields('d', ['weight'])
      ->execute()
      ->fetchField();

    if (!is_numeric($weight)) {
      $weight = 999999;
    }

    return $weight;
  }

}
