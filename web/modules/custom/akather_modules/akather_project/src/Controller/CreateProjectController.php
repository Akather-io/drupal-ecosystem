<?php

namespace Drupal\akather_project\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for Akather Project routes.
 */
class CreateProjectController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function index() {

    /** @var \Drupal\node\NodeForm $form_object */
    $node_form = \Drupal::entityTypeManager()
      ->getFormObject('node', 'default');
    $node = \Drupal::entityTypeManager()->getStorage('node')->create(['type' => 'project']);
    $form = \Drupal::service('entity.form_builder')->getForm($node, 'default');
    // $form = \Drupal::formBuilder()->getForm(\Drupal\akather_project\Form\CreateProjectForm::class);

    $build['content'] = [
      '#theme' => 'create_project_page',
      '#data' => [],
      '#form' => NULL,
    ];

    return $build;
  }

}
