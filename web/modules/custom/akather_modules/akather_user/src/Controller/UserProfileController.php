<?php

namespace Drupal\akather_user\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;

/**
 * Returns responses for Akather user routes.
 */
class UserProfileController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {

    $data = [];
    $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
    $uuid_str = str_replace("-", "", $user->uuid());
    $url_options = [
      'absolute' => TRUE,
      'language' => \Drupal::languageManager()->getCurrentLanguage(),
    ];
    $data['link_ref'] = Url::fromRoute('<front>', ['ref' => $uuid_str], $url_options)->toString();
    // $build['content'] = [
    //   '#type' => 'item',
    //   '#markup' => $this->t('It works!'),
    // ];

    $build['content'] = [
      '#theme' => 'user_profile',
      '#data' => $data,
    ];

    return $build;
  }

  /**
   * Builds the response.
   */
  public function loginPage() {

    $build['content'] = [
      '#theme' => 'user_login_page',
      '#data' => $data
    ];

    return $build;
  }

}
