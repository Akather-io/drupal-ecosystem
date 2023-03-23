(function ($, Drupal, window) {

  "use strict";

  let sdk;

  Drupal.behaviors.web3_evm = {
    attach: function (context, settings) {
    }
  };

  $(document).ready(function () {
    // console.log(connectors);
  });

  /**
 * Main entry point.
 */
  window.addEventListener('load', async () => {
    if (window.web3) {
      // await init();
      // console.log(connectors[4]);
      // $('#connect-wallet').click(function () {
      //   console.log('#connect-wallet');
      //   web3func.onConnect();
      // });

      // $('#disconnect-wallet').click(function () {
      //   console.log('#disconnect-wallet');
      //   onDisconnect();
      // });


      // $('#claim-nft').click(function () {
      //   console.log('#claim-nft');
      //   claimNFT();
      // });


      // $('#edit-submit--2').click(async function (event) {
      //   event.preventDefault();
      //   console.log('#edit-submit--2');
      //   // claimNFT();
      //   // await web3func.createProject();
      //   await deloyCampain();
      //   // console.log($('.form-item-field-cover-image a')[0].href);
      //   // $(this).submit();
      // });

    }
  });

  /**
 * Setup the orchestra
 */
  async function init() {

    // https://wagmi.sh/examples/connect-wallet
    // connectors = wagmiClient.connectors;
    // const signer = await fetchSigner();

    // sdk = ThirdwebSDK.fromSigner(signer, "mumbai");
  }

  //   /**
  //  * Connect wallet button pressed.
  //  */
  //   async function onConnect() {

  //     // console.log("List Connectors", connectors);
  //     const urlParams = new URLSearchParams(location.search);
  //     try {
  //       const connector = await connectors[4].connect();
  //       const signer = await fetchSigner();
  //       var data = $.parseJSON($.ajax({
  //         url: Drupal.url("wallet/signature"),
  //         type: "GET",
  //         contentType: "application/json; charset=utf-8",
  //         dataType: "json",
  //         async: false
  //       }).responseText);

  //       var signature = data[0];
  //       console.log("Signature: ", signature);

  //       data = $.parseJSON($.ajax({
  //         url: Drupal.url("wallet/login"),
  //         type: "POST",
  //         data: {
  //           "address": await signer.getAddress(),
  //           "signature": await signer.signMessage(signature),
  //           "ref": urlParams.get('ref'),
  //           "wref": urlParams.get('wref'),
  //         },
  //         async: false
  //       }).responseText);

  //       window.location.href = '/user/profile';

  //     } catch (e) {
  //       console.log("Could not get a wallet connection", e);
  //       return;
  //     }
  //   }

  // async function claimNFT() {
  //   // try {

  //   const signer = await fetchSigner();
  //   const address = await signer.getAddress();
  //   const sdk = ThirdwebSDK.fromSigner(signer, "mumbai");
  //   const projectContract = drupalSettings.projectContract;
  //   const contract = await sdk.getContract(projectContract, "nft-drop");
  //   const quantity = 1;
  //   const data = await contract.call("owner")
  //   console.log(data);
  //   const tx = await contract.claimTo(address, quantity);
  //   const receipt = tx[0].receipt; // the transaction receipt
  //   const claimedTokenId = tx[0].id; // the id of the NFT claimed
  //   const claimedNFT = await tx[0].data(); // (optional) get the claimed NFT metadata

  //   console.log(claimedTokenId);
  //   console.log(receipt);
  //   // } catch (e) {
  //   //   console.log("Could not get a wallet connection", e);
  //   //   return;
  //   // }
  // }

}(jQuery, Drupal, window));

function wait(milliseconds) {
  return new Promise(resolve => {
    setTimeout(resolve, milliseconds);
  });
}
