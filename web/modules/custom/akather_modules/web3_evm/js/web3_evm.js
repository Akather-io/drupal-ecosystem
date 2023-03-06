(function ($, Drupal, window) {

  "use strict";
  let connectors;

  Drupal.behaviors.web3_evm = {
    attach: function (context, settings) {
    }
  };

  $(document).ready(function () {
  });

  /**
 * Main entry point.
 */
  window.addEventListener('load', async () => {
    if (window.web3) {
      // https://wagmi.sh/examples/connect-wallet

      connectors = wagmiClient.connectors;
      // console.log(connectors[4]);

      $('#connect-wallet').click(function () {
        console.log('#connect-wallet');
        onConnect();
      });

      $('#disconnect-wallet').click(function () {
        console.log('#disconnect-wallet');
        onDisconnect();
      });
    }
  });

  /**
 * Connect wallet button pressed.
 */
  async function onConnect() {

    // console.log("List Connectors", connectors);
    const urlParams = new URLSearchParams(location.search);
    try {
      const connector = await connectors[4].connect();
      const signer = await fetchSigner();
      var data = $.parseJSON($.ajax({
        url: Drupal.url("wallet/signature"),
        type: "GET",
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        async: false
      }).responseText);

      var signature = data[0];
      console.log("Signature: ", signature);

      data = $.parseJSON($.ajax({
        url: Drupal.url("wallet/login"),
        type: "POST",
        data: {
          "address": await signer.getAddress(),
          "signature": await signer.signMessage(signature),
          "ref": urlParams.get('ref'),
          "wref": urlParams.get('wref'),
        },
        async: false
      }).responseText);

      window.location.href = '/user/profile';

    } catch (e) {
      console.log("Could not get a wallet connection", e);
      return;
    }
  }

  /**
 * Disconnect wallet button pressed.
 */
  async function onDisconnect() {
    console.log("Drupal logout...");
    var data = $.parseJSON($.ajax({
      url: Drupal.url("wallet/logout"),
      type: "GET",
      contentType: "application/json; charset=utf-8",
      dataType: "json",
      async: false
    }).responseText);

    window.location.reload(false);
  }

}(jQuery, Drupal, window));
