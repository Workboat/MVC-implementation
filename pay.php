<?php
require 'vendor/autoload.php';
require_once 'env.php';
?>
<html>
<head>
  <title>Payment Form</title>
  <!-- link to the SqPaymentForm library -->
  <script type="text/javascript" src=
    <?php
        echo "\"";
        echo ($_ENV["USE_PROD"] == 'true')  ?  "https://js.squareup.com/v2/paymentform"
                                            :  "https://js.squareupsandbox.com/v2/paymentform";
        echo "\"";
    ?>
  ></script>
  <script type="text/javascript">
    window.applicationId =
      <?php
        echo "\"";
        echo ($_ENV["USE_PROD"] == 'true')  ?  $_ENV["PROD_APP_ID"]
                                            :  $_ENV["SANDBOX_APP_ID"];
        echo "\"";
      ?>;
    window.locationId =
    <?php
      echo "\"";
      echo ($_ENV["USE_PROD"] == 'true')  ?  $_ENV["PROD_LOCATION_ID"]
                                          :  $_ENV["SANDBOX_LOCATION_ID"];
      echo "\"";
    ?>;
  </script>
  <script type="text/javascript" src="js/sq-payment-form.js"></script>
  <link rel="stylesheet" type="text/css" href="css/sq-payment-form.css">
</head>
<body>
  <!-- Begin Payment Form -->
  <div class="sq-payment-form" style="margin: 0 auto;">
    <div id="sq-walletbox">
      <button id="sq-google-pay" class="button-google-pay"></button>
      <button id="sq-apple-pay" class="sq-apple-pay"></button>
      <button id="sq-masterpass" class="sq-masterpass"></button>
      <div class="sq-wallet-divider">
        <span class="sq-wallet-divider__text">Or</span>
      </div>
    </div>
    <div id="sq-ccbox">
      <form id="nonce-form" novalidate action="/process-card.php" method="post">
        <div class="sq-field">
          <label class="sq-label">Card Number</label>
          <div id="sq-card-number"></div>
        </div>
        <div class="sq-field-wrapper">
          <div class="sq-field sq-field--in-wrapper">
            <label class="sq-label">CVV</label>
            <div id="sq-cvv"></div>
          </div>
          <div class="sq-field sq-field--in-wrapper">
            <label class="sq-label">Expiration</label>
            <div id="sq-expiration-date"></div>
          </div>
          <div class="sq-field sq-field--in-wrapper">
            <label class="sq-label">Postal</label>
            <div id="sq-postal-code"></div>
          </div>
        </div>
        <div class="sq-field">
          <button id="sq-creditcard" class="sq-button" onclick="onGetCardNonce(event)">
            Pay $<?= sanitizer($_GET['amount']) ?> Now
          </button>
        </div>
        <div id="error"></div>
        <input type="hidden" id="card-nonce" name="nonce">
        <input type="hidden" id="amount" name="amount" value="<?= sanitizer($_GET['amount']) ?>">
        <input type="hidden" id="email" name="email" value="<?= sanitizer($_GET['email']) ?>">
        <input type="hidden" id="name" name="name" value="<?= sanitizer($_GET['name']) ?>">
        <input type="hidden" id="phone" name="phone" value="<?= sanitizer($_GET['phone']) ?>">
        <input type="hidden" id="message" name="message" value="<?= sanitizer($_GET['message']) ?>">
        <input type="hidden" id="sizex" name="sizex" value="<?= sanitizer($_GET['size1']) ?>">
        <input type="hidden" id="sizey" name="sizey" value="<?= sanitizer($_GET['size2']) ?>">
        <input type="hidden" id="thumbnail" name="thumbnail" value="<?= sanitizer($_GET['thumbnail']) ?>">
      </form>
    </div>
  </div>
  <!-- End Payment Form -->
</body>
</html>
<?php
  function sanitizer($str){
    return htmlspecialchars($str, ENT_QUOTES, 'utf-8');
  }
?>