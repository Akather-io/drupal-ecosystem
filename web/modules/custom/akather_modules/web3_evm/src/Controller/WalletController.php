<?php

namespace Drupal\web3_evm\Controller;

use Drupal\Component\Utility\Crypt;
use Drupal\Component\Utility\Random;
use Drupal\Core\Controller\ControllerBase;
use Drupal\user\Entity\User;
use Elliptic\EC;
use kornrunner\Keccak;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Defines WalletController class.
 */
class WalletController extends ControllerBase {

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
  public function login(Request $request) {
    // Get signature and address from request.
    $request_signature = $request->request->get('signature');
    $request_wallet_address = $request->request->get('address');

    $session = $request->getSession();
    $nonce = $session->get('nonce');

    if (!$this->verify($nonce, $request_signature, $request_wallet_address)) {
      throw new \Exception('Signature verification failed.');
    }

    // If address not in drupal and not registered with a user, then create the
    // user then finish the login process.
    // If address in Drupal and connected to a user, then authenticate and
    // login the user.
    $users = \Drupal::entityTypeManager()->getStorage('user')->loadByProperties([
      'field_wallet_address' => $request_wallet_address,
    ]);

    // If user doesn't exist then create a new Drupal user.
    if (empty($users)) {
      $user = $this->createUser($request_wallet_address);
    }
    else {
      $user = reset($users);
    }

    // If user created/exists and found by wallet address and verified message
    // above then log them in.
    if (!empty($user)) {
      user_login_finalize($user);
    }

    return new JsonResponse($user->id());
  }

  /**
   * Helper function to create the user.
   *
   * @param string $wallet_address
   *   Wallet address for new user.
   *
   * @return \Drupal\user\Entity\User
   *   Return the new Drupal user.
   *
   * @throws Drupal\Core\Entity\EntityStorageException
   */
  public function createUser($wallet_address) {
    $user = User::create();

    // Substring of address to be used as username.
    // $sub_address = substr($wallet_address, 0, 15);
    $sub_address = $wallet_address;

    // Mandatory for Drupal, so set to a random number as not needed by us.
    $user->setPassword(Crypt::randomBytesBase64());
    $user->enforceIsNew();

    // Should it be something else considering we don't own address?
    // $user->setEmail($sub_address . '@web3_evm.com');

    $user->setUsername($sub_address);

    // Set wallet address.
    // @todo - double check in case field was removed manually by admin?
    $user->set('field_wallet_address', $wallet_address);

    $user->activate();

    // Save user account.
    $user->save();

    return $user;
  }

  /**
   * Return a signature string.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   Return the generated signature.
   */
  public function signature() {
    $signature = $this->generateSignature();

    return new JsonResponse([$signature]);
  }

  /**
   * Generate signature from nonce.
   *
   * @return string
   *   Return generated message with random nonce.
   */
  public function generateSignature() {
    $random_generator = new Random();

    // Generate a random 16 character nonce string.
    $nonce = $random_generator->string(16);

    // Save it in session.
    $request = \Drupal::request();
    $session = $request->getSession();
    $session->set('nonce', $nonce);

    return $this->generateMessage($nonce);
  }

  /**
   * Generate message to sign.
   *
   * @param string $nonce
   *   Random nonce string.
   *
   * @return string
   *   Return generated message to be signed.
   */
  public function generateMessage(string $nonce) {
    return str_replace(':nonce:', $nonce, "Hey! Sign this message to prove you have access to this wallet. This won't cost you anything.\n\nSecurity code (you can ignore this): :nonce:");
  }

  /**
   * Verify the signed message.
   *
   * @param string $nonce
   *   Random nonce string.
   * @param string $signature
   *   Signed message.
   * @param string $address
   *   Wallet address.
   *
   * @return bool
   *   Whether the message and signed message match.
   *
   * @throws \Exception
   */
  public function verify($nonce, $signature, $address) {
    $message = $this->generateMessage($nonce);

    $hash  = Keccak::hash(sprintf("\x19Ethereum Signed Message:\n%s%s", strlen($message), $message), 256);
    $sign  = ['r' => substr($signature, 2, 64), 's' => substr($signature, 66, 64)];
    $recid = ord(hex2bin(substr($signature, 130, 2))) - 27;

    if ($recid != ($recid & 1)) {
      return FALSE;
    }

    $pubkey = (new EC('secp256k1'))->recoverPubKey($hash, $sign, $recid);

    return hash_equals(
      strtolower(array_reverse(explode('0x', $address, 2))[0]),
      substr(Keccak::hash(substr(hex2bin($pubkey->encode('hex')), 1), 256), 24)
    );
  }

  /**
   * Logout user when wallet disconnects from site.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   Empty JSON string returned as nothing is expected.
   */
  public function logout() {
    // Logout user from Drupal.
    user_logout();

    return new JsonResponse('');
  }

}
