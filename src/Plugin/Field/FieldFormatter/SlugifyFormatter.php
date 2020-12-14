<?php

namespace Drupal\silly_text_formatters\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FormatterBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Cocur\Slugify\SlugifyInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'Slugify' formatter.
 *
 * @FieldFormatter(
 *   id = "silly_text_formatters_slugify",
 *   label = @Translation("Slugify"),
 *   field_types = {
 *     "list_string", "string", "string_long", "text", "text_long",
 *     "text_with_summary"
 *   }
 * )
 */
class SlugifyFormatter extends FormatterBase {

  /**
   * The Slugify service
   *
   * @var \Cocur\Slugify\SlugifyInterface
   */
  protected $slug;

  /**
   * Construct a SlugifyFormatter object.
   *
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   *   Defines an interface for entity field definitions.
   * @param array $settings
   *   The formatter settings.
   * @param string $label
   *   The formatter label display setting.
   * @param string $view_mode
   *   The view mode.
   * @param array $third_party_settings
   *   Any third party settings.
   * @param Cocur\Slugify\SlugifyInterface $slug
   *   Slugify service.
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, $label, $view_mode, array $third_party_settings, SlugifyInterface $slug) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $label, $view_mode, $third_party_settings);

    $this->slug = $slug;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['label'],
      $configuration['view_mode'],
      $configuration['third_party_settings'],
      $container->get('cocur.slugify')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];

    foreach ($items as $delta => $item) {
      $element[$delta] = [
        '#markup' => $this->slug->slugify($item->value, $this->getSetting('separator')),
      ];
    }

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'separator' => '',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $elements = parent::settingsForm($form, $form_state);

    $elements['separator'] = [
      '#type' => 'textfield',
      '#title' => t('Slugify Seperator'),
      '#size' => 1,
      '#default_value' => $this->getSetting('separator'),
    ];

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    $summary[] = t('Seperator: @separator', ['@separator' => $this->getSetting('separator')]);

    return $summary;
  }

}
