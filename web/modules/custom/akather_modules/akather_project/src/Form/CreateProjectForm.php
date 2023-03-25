<?php

namespace Drupal\akather_project\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface;

/**
 * Provides a Akather project form.
 */
class CreateProjectForm extends FormBase {

  /**
   * Current user account.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * Node storage.
   *
   * @var \Drupal\node\NodeStorageInterface
   */
  protected $nodeManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(
    EntityTypeManager $entity_type_manager,
    AccountProxyInterface $current_user
  ) {
    $this->currentUser = $current_user;
    $this->nodeManager = $entity_type_manager->getStorage('node');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('current_user')
    );
  }

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
    $field_catagories = $form_project['field_catagories']['widget']['#options'];
    // dump($field_catagories);die;
    unset($field_catagories['_none']);
    $form['field_catagories'] = [
      '#type' => 'select',
      // '#title' => $this->t('Catagories'),
      "#options" => $field_catagories,
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
      '#required' => TRUE,
      // '#title' => $this->t('Quantity'),
    ];
    $form['field_release_time'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      // '#title' => $this->t('Release Time'),
      '#attributes' => [
        'placeholder' => $this->t('Release Time'),
      ],
    ];
    $form['field_time_line'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      // '#title' => $this->t('TimeLine'),
      '#attributes' => [
        'placeholder' => $this->t('TimeLine'),
      ],
    ];
    $field_token = $form_project['field_token']['widget']['#options'];
    unset($field_token['_none']);
    $form['field_token'] = [
      '#type' => 'select',
      // '#title' => $this->t('Token'),
      "#options" => $field_token,
      '#default_value' => key($field_token),
      '#required' => TRUE,
    ];
    $form['field_total_fund'] = [
      '#type' => 'textfield',
      // '#title' => $this->t('Total Fund'),
    ];
    $field_type  = $form_project['field_type']['widget']['#options'];
    unset($field_type['_none']);
    $form['field_type'] = [
      '#type' => 'select',
      // '#title' => $this->t('Type'),
      "#options" => $field_type,
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
    // if (mb_strlen($form_state->getValue('message')) < 10) {
    //   $form_state->setErrorByName('message', $this->t('Message should be at least 10 characters.'));
    // }
    // dump($form_state);die;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $time_zone = new \DateTimeZone('UTC');
    // $node = \Drupal\node\Entity\Node::load(67);

    // // $field_release_time = DrupalDateTime::createFromFormat('Y-m-d\TH:i:s', '2022-06-07T20:45:00');
    // dump($node);
    // $values['field_time_line'] .= "T00:00:00";
    // $field_time_line = DrupalDateTime::createFromFormat('d/m/Y\TH:i:s', $values['field_time_line'], $time_zone);
    // $field_time_line_end = DrupalDateTime::createFromTimestamp(strtotime('+1 months', $field_time_line->getTimestamp()), $time_zone);
    // dump($field_time_line->format(DateTimeItemInterface::DATETIME_STORAGE_FORMAT));
    // dump($field_time_line_end->format(DateTimeItemInterface::DATETIME_STORAGE_FORMAT));
    // // dump($values);
    // die;

    // $this->messenger()->addStatus($this->t('The message has been sent.'));
    // $form_state->setRedirect('<front>');

    $node = $this->nodeManager->create([
      'type' => 'project',
      'title' => $values['title'],
      'uid' => $this->currentUser->id(),
      'status' => 1,
    ]);
    // $node->field_description->value = 'body';
    // $node->field_description->format = 'full_html';//omitting this will retain existing format
    // $node->field_description->setValue([
    //   'value' => $values['field_description']['value'],
    //   'format' => $values['field_description']['format']
    // ]);
    $node->field_description->setValue($values['field_description']);
    $node->field_benefit->setValue($values['field_benefit']);
    $node->field_catagories->target_id = $values['field_catagories'];
    $node->field_cover_image->target_id = $values['field_cover_image'][0];
    $node->field_quantity->value = $values['field_quantity'];


    $values['field_release_time'] .= "T00:00:00";
    $field_release_time = DrupalDateTime::createFromFormat('d/m/Y\TH:i:s', $values['field_release_time'], $time_zone);
    // $node->field_release_time->value = $field_release_time->format(DateTimeItemInterface::DATE_STORAGE_FORMAT);
    $node->set('field_release_time', $field_release_time->format(DateTimeItemInterface::DATETIME_STORAGE_FORMAT));

    $values['field_time_line'] .= "T00:00:00";
    $field_time_line = DrupalDateTime::createFromFormat('d/m/Y\TH:i:s', $values['field_time_line'],$time_zone);
    $field_time_line_start = DrupalDateTime::createFromTimestamp(strtotime('-1 day', $field_time_line->getTimestamp()), $time_zone);
    $field_time_line_end = DrupalDateTime::createFromTimestamp(strtotime('+1 months', $field_time_line->getTimestamp()), $time_zone);
    $node->field_time_line->value = $field_time_line_start->format(DateTimeItemInterface::DATETIME_STORAGE_FORMAT);
    $node->field_time_line->end_value = $field_time_line_end->format(DateTimeItemInterface::DATETIME_STORAGE_FORMAT);
    $node->field_time_line->end_value = $field_release_time->format(DateTimeItemInterface::DATETIME_STORAGE_FORMAT);

    $node->field_token->target_id = $values['field_token'];
    $node->field_total_fund->value = $values['field_total_fund'];
    $node->field_type->target_id = $values['field_type'];
    $node->field_your_sketch->target_id = $values['field_your_sketch'][0];
    $node->field_contract->value = $values['field_contract'];

    $node->save();

    \Drupal::messenger()->addMessage(t("Project Registration Done!!"));
    // foreach ($form_state->getValues() as $key => $value) {
    //   \Drupal::messenger()->addMessage($key . ': ' . $value);
    //   }
  }

}
