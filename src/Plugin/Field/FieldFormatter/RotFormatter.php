<?php

namespace Drupal\silly_text_formatters\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'rot' formatter.
 *
 * @FieldFormatter(
 *   id = "silly_text_formatters_rot",
 *   label = @Translation("Rot Encoder"),
 *   field_types = {
 *     "list_string", "string", "string_long", "text", "text_long",
 *     "text_with_summary"
 *   }
 * )
 */
class RotFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];

    foreach ($items as $delta => $item) {
      $element[$delta] = [
        '#markup' => $this->rot($item->value, $this->getSetting('rot_level')),
      ];
    }
    return $element;
  }

  /**
   *  rot encoding
   * @param String $string
   *  The string to be encoded
   * @param Int $level
   *  The level of encoding (26 is twice as secure as 13)
   *  rot("plain text", 13) returns cynva grkg
   */
  public function rot(String $string, Int $level = 0) {
    //split string up into a char array
    foreach (str_split($string) as $key => $value) {
      if (ctype_alpha($value)) { // leave none alpha chars alone
        // convert to int
        $char = ord($value);
        // uppercase or lowercase offet (65 or 97)
        $case_offet = (!ctype_lower($char)) ? ord('A') : ord('a');
        // subtract offset and mod 26 to wrap, add offset back
        $char = (($char - $case_offet + $level) % 26) + $case_offet;
        // back into a char
        $char = chr($char);
        // back into the string
        $string[$key] = $char;
      }
    }
    // return encoded string
    return $string;
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      // Declare a setting named 'rot_level', with
      // a default value of 13
      'rot_level' => 13,
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $form['rot_level'] = [
      '#title' => $this->t('Encoding Level 0-26'),
      '#type' => 'number',
      '#min' => 0,
      '#max' => 26,
      '#default_value' => $this->getSetting('rot_level'),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary()
  {
    $summary = [];
    $summary[] = t('Rot encoding level: @rot_level', ['@rot_level' => $this->getSetting('rot_level')]);
    return $summary;
  }

}
