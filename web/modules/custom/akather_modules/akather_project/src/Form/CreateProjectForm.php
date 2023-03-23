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
    return 'create_project';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // https://gorannikolovski.com/snippet/how-programmatically-render-entity-form
    $node = \Drupal::entityTypeManager()->getStorage('node')->create(['type' => 'project']);
    $form_project = \Drupal::service('entity.form_builder')->getForm($node, 'create_project');

    // dump($form_project);
    // dump($form_project['field_your_sketch']['widget'][0]);
    // die;

    $form['title'] = [
      '#type' => 'textfield',
      // '#title' => $this->t('Name'),
      '#required' => TRUE,
      '#maxlength' => 256,
      '#default_value' => '',
    ];
    $form['field_description'] = [
      '#type' => 'text_format',
      // '#title' => $this->t('Description'),
      "#format" => "basic_html",
      "#base_type" => "textarea",
      '#required' => TRUE,
      '#default_value' => '',
    ];
    $form['field_benefit'] = [
      '#type' => 'text_format',
      // '#title' => $this->t('Benefit'),
      "#format" => "basic_html",
      "#base_type" => "textarea",
      '#required' => TRUE,
      '#default_value' => '',
    ];
    $form['field_catagories'] = [
      '#type' => 'select',
      // '#title' => $this->t('Catagories'),
      "#options" => $form_project['field_catagories']['widget']['#options'],
      '#required' => TRUE,
    ];
    $form['field_cover_image'] = [
      // '#title' => $this->t('Cover Image'),
      // '#description' => t('Select a picture of at least @dimensionspx and maximum @filesize.', array(
      //   '@dimensions' => '100x100',
      //   '@filesize' => format_size(file_upload_max_size()),
      // )),
      '#type' => 'managed_file',
      '#upload_location' => 'public://create_project/cover_image',
      '#multiple' => FALSE,
      // '#description' => t('Allowed extensions: gif png jpg jpeg'),
      '#upload_validators' => [
        'file_validate_is_image' => array(),
        'file_validate_extensions' => array('gif png jpg jpeg'),
        'file_validate_size' => array(25600000)
      ],
      "#accept" => "image/*",
      "#preview_image_style" => "thumbnail",
    ];
    $form['field_quantity'] = [
      '#type' => 'textfield',
      // '#title' => $this->t('Quantity'),
    ];
    $form['field_release_time'] = [
      '#type' => 'textfield',
      // '#title' => $this->t('Release Time'),
    ];
    $form['field_time_line'] = [
      '#type' => 'textfield',
      // '#title' => $this->t('TimeLine'),
    ];
    $form['field_token'] = [
      '#type' => 'select',
      // '#title' => $this->t('Token'),
      "#options" => $form_project['field_token']['widget']['#options'],
      '#required' => TRUE,
    ];
    $form['field_total_fund'] = [
      '#type' => 'textfield',
      // '#title' => $this->t('Total Fund'),
    ];
    $form['field_type'] = [
      '#type' => 'select',
      // '#title' => $this->t('Type'),
      "#options" => $form_project['field_type']['widget']['#options'],
      '#required' => TRUE,
    ];

    $form['field_your_sketch'] = [
      // '#title' => $this->t('Your Sketch'),
      '#type' => 'managed_file',
      '#upload_location' => 'public://create_project/your_sketch',
      '#multiple' => FALSE,
      '#description' => t('Allowed extensions: pdf'),
      '#upload_validators' => [
        'file_validate_extensions' => array('pdf'),
        'file_validate_size' => array(25600000)
      ],
    ];
    $form['field_contract'] = [
      '#type' => 'hidden',
      // '#title' => $this->t('Contract'),
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    $form['#theme'] = 'create_project_page';
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
    // https://www.berramou.com/blog/drupal-8-9-build-form-state-from-node-entity
    // https://stackoverflow.com/questions/64970343/drupal-8-or-9-save-data-to-the-content-type-from-a-custom-module
    // https://drupal.stackexchange.com/questions/216107/how-to-use-the-managed-file-field-in-a-custom-form-plugin
    // https://www.rapiddg.com/article/adding-image-uploads-custom-module-drupal
    $this->messenger()->addStatus($this->t('The message has been sent.'));
    // $form_state->setRedirect('<front>');
  }

}
