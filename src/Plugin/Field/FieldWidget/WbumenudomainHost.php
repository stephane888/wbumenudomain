<?php

namespace Drupal\wbumenudomain\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\wbumenudomain\Wbumenudomain;
use Stephane888\Debug\debugLog;

/**
 * A widget bar.
 *
 * @FieldWidget(
 *   id = "wbumenudomainhost",
 *   label = @Translation(" Wbumenudomain Widget Host "),
 *   field_types = {
 *     "wbumenudomaineditlink",
 *   }
 * )
 */
class WbumenudomainHost extends WidgetBase {
  
  /**
   *
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'placeholder' => ''
    ] + parent::defaultSettings();
  }
  
  /**
   *
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $entityTypeId = $items->getEntity()->getEntityTypeId();
    $value = isset($items[$delta]->value) ? $items[$delta]->value : '';
    $element['value'] = $element + [
      '#type' => 'select',
      '#default_value' => $value,
      '#options' => Wbumenudomain::getUnUseDomain($value, $entityTypeId),
      '#required' => true,
      "#empty_option" => t('- Selectionner un domaine -')
    ];
    return $element;
  }
  
  /**
   *
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $element['placeholder'] = [
      '#type' => 'textfield',
      '#title' => t('Placeholder'),
      '#default_value' => $this->getSetting('placeholder'),
      '#description' => t(' Text that will be shown inside the field until a value is entered. This hint is usually a sample value or a brief description of the expected format. ')
    ];
    return $element;
  }
  
}