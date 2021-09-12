<?php

namespace Drupal\wbumenudomain\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;


/**
 * Class ContentEntityExampleSettingsForm.
 *
 * @package Drupal\content_entity_example\Form
 * @ingroup content_entity_example
 */
class WbumenudomainSettingsForm extends FormBase
{

	/**
	 * Returns a unique string identifying the form.
	 *
	 * @return string The unique string identifying the form.
	 */
	public function getFormId()
	{
		return 'wbumenudomain_settings';
	}

	/**
	 * Form submission handler.
	 *
	 * @param array $form
	 *        	An associative array containing the structure of the form.
	 * @param FormStateInterface $form_state
	 *        	An associative array containing the current state of the form.
	 */
	public function submitForm(array &$form, FormStateInterface $form_state)
	{
		// Empty implementation of the abstract submit class.
	}

	/**
	 * Define the form used for ContentEntityExample settings.
	 *
	 * @return array Form definition array.
	 *        
	 * @param array $form
	 *        	An associative array containing the structure of the form.
	 * @param FormStateInterface $form_state
	 *        	An associative array containing the current state of the form.
	 */
	public function buildForm(array $form, FormStateInterface $form_state)
	{
		$form['wbumenudomain_settings']['#markup'] = 'Settings form for ContentEntityExample. Manage field settings here.';
		return $form;
	}
}