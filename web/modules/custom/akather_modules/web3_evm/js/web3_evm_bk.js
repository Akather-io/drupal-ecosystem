(function ($, Drupal, window) {

  "use strict";

  Drupal.behaviors.web3_evm = {
    attach: function (context, settings) {
      console.log('Akather Web3 EVM : It works!');
    }
  };

  $(document).ready(function () {

  });

  // Unpkg imports
  // Standalone wbe3modal.
  const Web3Modal = window.Web3Modal.default;
  const WalletConnectProvider = window.WalletConnectProvider.default;
  const EvmChains = window.evmChains;
  const Fortmatic = window.Fortmatic;

  // Web3modal instance
  let web3Modal;

  // Chosen wallet provider given by the dialog window
  let provider;

  // Address of the selected account
  let selectedAccount;

  let web3ethers;

  /**
 * Setup the orchestra
 */
  function init() {
    const providerOptions = {};

    web3Modal = new Web3Modal({
      // network: "mainnet", // optional
      cacheProvider: false, // optional
      // disableInjectedProvider: true,
      providerOptions // required
    });
  }

  /**
 * Kick in the UI action after Web3modal dialog has chosen a provider
 */
  async function fetchAccountData() {

    // Get a Web3 instance for the wallet
    const web3 = new Web3(provider);

    // Get connected chain id from Ethereum node
    const chainId = await web3.eth.getChainId();
    // Load chain information over an HTTP API
    const chainData = await EvmChains.getChain(chainId);
    // document.querySelector("#network-name").textContent = chainData.name;
    $('span.wallet-chain').text(chainData.name);
    console.log(chainData.name);

    // Get list of accounts of the connected wallet
    const accounts = await web3.eth.getAccounts();

    // MetaMask does not give you all accounts, only the selected account
    console.log("Got accounts", accounts);
    selectedAccount = accounts[0];

    web3ethers = new ethers.providers.Web3Provider(provider);
    var balance = await web3ethers.getSigner().getBalance();
    balance = ethers.utils.formatEther(balance);
    console.log("Balance: ", await web3ethers.getSigner().getBalance());
    $('span.wallet-balance').text(balance);
  }

  /**
 * Main entry point.
 */
  window.addEventListener('load', async () => {
    if (window.web3) {
      // init();
      // onLoadPage();

      // document.querySelector("#btn-connect").addEventListener("click", onConnect);
      $('#connect-wallet').click(function () {
        console.log('#connect-wallet');
        onConnect();
      });
      // document.querySelector("#btn-disconnect").addEventListener("click", onDisconnect);
      $('#disconnect-wallet').click(function () {
        console.log('#disconnect-wallet');
        onDisconnect();
      });
    }
  });

  async function onLoadPage() {
    // Get user id.
    var uid = drupalSettings.user.uid;
    if (uid !== 1) {
      provider = await web3Modal.connect();
      console.log("Provider: ", provider);

      if (uid !== 0) { // User logged
        console.log('User logged in... initialise wallet connection...');
      } else { // User not login

      }
    }
  }

  /**
 * Connect wallet button pressed.
 */
  async function onConnect() {

    console.log("Opening a dialog", web3Modal);
    try {

      provider = await web3Modal.connect();
      console.log("Provider: ", provider);

      const web3ethers = new ethers.providers.Web3Provider(provider);
      console.log("Wallet address: ", web3ethers.getSigner().getAddress());

      var data = $.parseJSON($.ajax({
        url: Drupal.url("wallet/signature"),
        type: "GET",
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        async: false
      }).responseText);

      var signature = data[0];
      console.log("Signature: ", signature);

      const urlParams = new URLSearchParams(location.search);

      data = $.parseJSON($.ajax({
        url: Drupal.url("wallet/login"),
        type: "POST",
        data: {
          "address": await web3ethers.getSigner().getAddress(),
          "signature": await web3ethers.getSigner().signMessage(signature),
          "ref": urlParams.get('ref'),
          "wref": urlParams.get('wref'),
        },
        async: false
      }).responseText);

      // console.log("Drupal Login: ", data);
      // window.location.reload(false);
      window.location.href = '/user';

    } catch (e) {
      console.log("Could not get a wallet connection", e);
      return;
    }

    // Subscribe to accounts change
    provider.on("accountsChanged", (accounts) => {
      fetchAccountData();
    });

    // Subscribe to chainId change
    provider.on("chainChanged", (chainId) => {
      fetchAccountData();
    });

    // Subscribe to networkId change
    provider.on("networkChanged", (networkId) => {
      fetchAccountData();
    });

    await refreshAccountData();
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

  /**
 * Fetch account data for UI when
 * - User switches accounts in wallet
 * - User switches networks in wallet
 * - User connects wallet initially
 */
  async function refreshAccountData() {
    await fetchAccountData(provider);
  }

}(jQuery, Drupal, window));
