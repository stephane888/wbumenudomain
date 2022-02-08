<?php

namespace Drupal\wbumenudomain;

use Drupal\Core\Form\FormStateInterface;

/**
 * Permet de decouper le formulaire en multi step.
 *
 * @author stephane
 *        
 */
class WbumenuDomainFormStep {
  protected static $fieldsStep = [
    1 => [
      'title',
      'body'
    ],
    2 => [
      'field_description_du_partenaire'
    ]
  ];
  protected $form;
  protected $formState;
  
  function __construct(&$form, FormStateInterface $form_state) {
    $this->form = $form;
    $this->formState = $form_state;
    
    $this->currentStep = 0;
  }
  
  public function build() {
    // \Drupal::messenger()->addStatus('val : ' .
    // $this->formState->get('page_num') . ' => ' . get_called_class());
    if ($this->formState->has('page_num')) {
      dump('GOOOOOOOOD');
      $page_num = $this->formState->get('page_num');
      if ($page_num == 2) {
        $this->form = $this->steps(2);
      }
    }
    else {
      $this->formState->set('page_num', 1);
      $this->form = $this->steps();
    }
    return $this->form;
  }
  
  protected function steps($id = 1) {
    $newForm = [];
    $newForm['description'] = [
      '#type' => 'item',
      '#title' => ' etapes 1 ',
      '#weight' => -100
    ];
    $fieldsStep = self::$fieldsStep[$id];
    foreach ($this->form as $key => $value) {
      if (in_array($key, $fieldsStep) || $key == 'actions') {
        $newForm[$key] = $value;
      }
      elseif (!isset($value["#type"])) {
        $newForm[$key] = $value;
      }
    }
    // $form = $this->form;
    // $form_state = $this->formState;
    $submit_handlers = $this->formState->getSubmitHandlers();
    $submit_handlers[] = 'wbumenudomain__next_step';
    $this->formState->setSubmitHandlers($submit_handlers);
    //
    $newForm['actions']['next'] = [
      '#type' => 'submit',
      '#button_type' => 'primary',
      '#value' => 'Etape suivante ...',
      '#attributes' => [
        'class' => [
          'mt-3'
        ]
      ],
      '#validate' => [ // 'wbumenudomain__next_step'
      ],
      '#submit' => [ // '::MultistepFormNextSubmit',
                      // [
                      // 'Drupal\wbumenudomain\WbumenuDomainFormStep',
                      // 'MultistepFormNextSubmit'
                      // ],
                      // [
                      // '\Drupal\wbumenudomain\WbumenuDomainFormStep',
                      // 'MultistepFormNextSubmit'
                      // ],
                      // [
                      // $this,
                      // 'MultistepFormNextSubmit'
                      // ]
                      // $this->MultistepFormNextSubmit($form, $form_state)
      ]
    ];
    return $newForm;
  }
  
  public function MultistepFormNextSubmit(array &$form, FormStateInterface $form_state) {
    dump($form_state->get('page_num'));
    $form_state->set('page_num', 2)->setRebuild(TRUE);
    dump($form_state->get('page_num'));
  }
  
}