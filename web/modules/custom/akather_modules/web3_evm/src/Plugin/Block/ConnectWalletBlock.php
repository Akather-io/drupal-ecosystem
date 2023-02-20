<?php

namespace Drupal\web3_evm\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\user\Entity\User;

/**
 * Block provides a 'Connect Wallet' button.
 *
 * @Block(
 *   id = "connect_wallet_block",
 *   admin_label = @Translation("Connect Wallet EVM"),
 *   category = @Translation("WEB3-EVM"),
 * )
 */
class ConnectWalletBlock extends BlockBase
{

    /**
     * {@inheritdoc}
     */
    public function build()
    {
      $current_user = \Drupal::currentUser();
      $logged_in = $current_user->isAuthenticated();
      $wallet_address = '';

      if ($logged_in) {
        $user = User::load($current_user->id());
        $wallet_address = $user->field_wallet_address->value;
        $wallet_address = substr($wallet_address, 0, 5) . '...' . substr($wallet_address, -4);

        $markup = $this->t('', ['%wallet_address' => $wallet_address]);
      }

        $data = [
          'wallet_address' => $wallet_address,
        ];

        return [
            '#theme' => 'connect_wallet_block',
            '#attached' => [
              'library' => [
                'web3_evm/web3_evm',
              ],
            ],
            '#data' => $data
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function blockAccess(AccountInterface $account)
    {
      return AccessResult::allowedIfHasPermission($account, 'access content');
    }

    /**
     * {@inheritdoc}
     */
    public function blockForm($form, FormStateInterface $form_state)
    {
      $config = $this->getConfiguration();

      return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function blockSubmit($form, FormStateInterface $form_state)
    {
      $this->configuration['connect_wallet_settings'] = $form_state->getValue('connect_wallet_settings');
    }

}
