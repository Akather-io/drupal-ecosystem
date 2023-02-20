<?php

namespace Drupal\user_ref\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\user\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Defines RefTestController class.
 */
class RefTestController extends ControllerBase {

  /**
   * Callback method for login route.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   Drupal Request.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   Return a JSON with logged in user ID or error.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function index(Request $request) {
    // Get signature and address from request.
    $ref = $request->request->get('ref');
    $wref = $request->request->get('wref');

    $currentUserId = \Drupal::currentUser()->id();
    $user = User::load($currentUserId);

    $uuid_str = str_replace("-", "", $user->uuid());

    $user_ref = get_user_ref($uuid_str);
    dump($user_ref);
    die;

    return new JsonResponse($user->id());
  }

}
