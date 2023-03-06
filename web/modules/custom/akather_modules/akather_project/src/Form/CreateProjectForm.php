<?php

namespace Drupal\akather_project\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a Akather project form.
 */
class CreateProjectForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'akather_project_create_project';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // https://gorannikolovski.com/snippet/how-programmatically-render-entity-form
    // Load existing node
    // $node = \Drupal\node\Entity\Node::load(1);
    // or create a new node
    $node = \Drupal::entityTypeManager()->getStorage('node')->create(['type' => 'project']);

    // $form = \Drupal::service('entity.form_builder')->getForm($node);
    $form = \Drupal::service('entity.form_builder')->getForm($node, 'default');

    // $form['message'] = [
    //   '#type' => 'textarea',
    //   '#title' => $this->t('Message'),
    //   '#required' => TRUE,
    // ];

    // $form['actions'] = [
    //   '#type' => 'actions',
    // ];
    // $form['actions']['submit'] = [
    //   '#type' => 'submit',
    //   '#value' => $this->t('Send'),
    // ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (mb_strlen($form_state->getValue('message')) < 10) {
      $form_state->setErrorByName('message', $this->t('Message should be at least 10 characters.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->messenger()->addStatus($this->t('The message has been sent.'));
    $form_state->setRedirect('<front>');
  }

}
