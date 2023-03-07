<?php

namespace Drupal\akather_book\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Book Block.
 *
 * @Block(
 *   id = "akather_book_block",
 *   admin_label = @Translation("Akather Book Block"),
 *   category = @Translation("Akather"),
 * )
 */
class BookBlock extends BlockBase
{
    /**
   * {@inheritdoc}
   */
    public function defaultConfiguration() {
        return [
            'title' => $this->t(''),
            'description' => $this->t(''),
        ];
    }

    /**
   * {@inheritdoc}
   */
    public function blockForm($form, FormStateInterface $form_state) {
        // $form['title'] = [
        //     '#type' => 'textfield',
        //     '#title' => $this->t('Title'),
        //     '#description' => $this->t('Title'),
        //     '#default_value' => $this->configuration['title'],
        // ];

        // $description = $this->configuration['description'];
        // $form['description'] = [
        //     '#type' => 'text_format',
        //     '#title' => $this->t('Description'),
        //     '#format' => isset($description['format']) ? $description['format'] : 'basic_html',
        //     '#allowed_formats' => ['basic_html'],
        //     '#cols' => 80,
        //     '#rows' => 7,
        //     '#description' => $this->t('Description'),
        //     '#default_value' => isset($description['value']) ? $description['value'] : '',
        // ];

        // $form['pre_order_link'] = [
        //     '#type' => 'textfield',
        //     '#title' => $this->t('Pre Order Link'),
        //     '#description' => $this->t('Pre Order Link'),
        //     '#default_value' => $this->configuration['pre_order_link'],
        // ];

        return $form;
    }

    /**
   * {@inheritdoc}
   */
    public function blockSubmit($form, FormStateInterface $form_state) {
        $values = $form_state->getValues();
        $this->configuration['description'] = $values['description'];
        $this->configuration['title'] = $values['title'];
        $this->configuration['pre_order_link'] = $values['pre_order_link'];
    }

    /**
   * {@inheritdoc}
   */
    public function blockValidate($form, FormStateInterface $form_state) {
        // $description = $form_state->getValue('description');

        // if ( $description['value'] === '') {
        //     $form_state->setErrorByName('description', $this->t('You can not empty description.'));
        // }
    }

    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $data = [
            'title' => $this->configuration['title'],
            'description' => $this->configuration['description'],
            'pre_order_link' => $this->configuration['pre_order_link'],
        ];

        return [
            '#theme' => 'akather_book_block',
            '#attached' => [
              'library' => [
                'akather_book/akather_book',
              ],
            ],
            '#data' => $data,
        ];
    }

}
