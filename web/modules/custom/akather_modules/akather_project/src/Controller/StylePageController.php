<?php

namespace Drupal\akather_project\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for Akather Project routes.
 */
class StylePageController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function index() {

    $build['content'] = [
      '#theme' => 'style_page',
      '#data' => [],
    ];

    return $build;
  }

}
