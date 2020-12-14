<?php

namespace Drupal\silly_text_formatters\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'Tool Tip' formatter.
 *
 * @FieldFormatter(
 *   id = "silly_text_formatters_tool_tip",
 *   label = @Translation("Tool Tip"),
 *   field_types = {
 *     "list_string", "string", "string_long", "text", "text_long",
 *     "text_with_summary"
 *   }
 * )
 */
class ToolTipFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];

    foreach ($items as $delta => $item) {
      $element[$delta] = [
        '#theme' => 'silly_tooltip',
        '#text' => $item->value,
        '#format' => $item->format,
        '#tooltip' => $this->getSetting('tip'),
      ];
    }

    $element[$delta]['#attached']['library'][] = 'silly_text_formatters/tooltip';

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings()
  {
    return [
      'tip' => 'Tool Tip!!',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state)
  {
    $elements = parent::settingsForm($form, $form_state);

    $elements['tip'] = [
      '#type' => 'textfield',
      '#title' => t('Tool Tip'),
      '#size' => 50,
      '#default_value' => $this->getSetting('tip'),
    ];

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary()
  {
    $summary = [];
    $summary[] = t('Tip: @tip', ['@tip' => $this->getSetting('tip')]);

    return $summary;
  }

}
