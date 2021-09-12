<?php

namespace Drupal\wbumenudomain\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Language\Language;
use Drupal\Core\Form\FormStateInterface;


/**
 * Form controller for the content_entity_example entity edit forms.
 *
 * @ingroup content_entity_example
 */
class WbumenudomainForm extends ContentEntityForm
{

	/**
	 *
	 * {@inheritdoc}
	 */
	public function buildForm(array $form, FormStateInterface $form_state)
	{
		/* @var $entity \Drupal\wbumenudomain\Entity\Wbumenudomain */
		$form = parent::buildForm($form, $form_state);
		$entity = $this->entity;

		$form['langcode'] = array(
				'#title' => $this->t('Language'),
				'#type' => 'language_select',
				'#default_value' => $entity->getUntranslated()->language()->getId(),
				'#languages' => Language::STATE_ALL
		);
		return $form;
	}

	/**
	 *
	 * {@inheritdoc}
	 */
	public function save(array $form, FormStateInterface $form_state)
	{
		$status = parent::save($form, $form_state);

		$entity = $this->entity;
		if ($status == SAVED_UPDATED) {
			$this->messenger()->addMessage($this->t('The Wbumenudomain %feed has been updated.', [
					'%feed' => $entity->toLink()->toString()
			]));
		}
		else {
			$this->messenger()->addMessage($this->t('The Wbumenudomain %feed has been added.', [
					'%feed' => $entity->toLink()->toString()
			]));
		}

		$form_state->setRedirectUrl($this->entity->toUrl('collection'));
		return $status;
	}
}
