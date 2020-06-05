<?php
require 'vendor/autoload.php';
require_once 'OrderNotifier.php';
require_once 'CustomPayment.php';
require_once 'Router.php';
require_once 'env.php';

$access_token = ($_ENV["USE_PROD"] == 'true')  ?  $_ENV["PROD_ACCESS_TOKEN"]
                                               :  $_ENV["SANDBOX_ACCESS_TOKEN"];

$host_url = ($_ENV["USE_PROD"] == 'true')  ?  "https://connect.squareup.com"
                                           :  "https://connect.squareupsandbox.com";

$api_config = new \SquareConnect\Configuration();
$api_config->setHost($host_url);
$api_config->setAccessToken($access_token);
$api_client = new \SquareConnect\ApiClient($api_config);

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
  error_log("Received a non-POST request");
  echo "Request not allowed";
  http_response_code(405);
  return;
}

# Fail if the card form didn't send a value for `nonce` to the server
$nonce = $_POST['nonce'];
if (is_null($nonce)) {
  echo "Invalid card data";
  http_response_code(422);
  return;
}

$payments_api = new CustomPayment($api_client);
$request_body = array (
  "source_id" => $nonce,
  "amount_money" => array (
    "amount" => (int)$_POST['amount']*100,
    "currency" => "CAD"
  ),
  "idempotency_key" => uniqid()
);

try {
  $result  = $payments_api->createPayment($request_body);
  $payment = $result->getPayment()->getStatus();
  $mailer  = new OrderNotifier;
  $fields  = [
         'Name'      => sanitizer($_POST['name']),
         'Email'     => sanitizer($_POST['email']),
         'Phone'     => sanitizer($_POST['phone']),
         'Size x'    => sanitizer($_POST['sizex']),
         'Size y'    => sanitizer($_POST['sizey']),
         'Message'   => sanitizer($_POST['message']),
         'Thumbnail' => urlencode($_POST['thumbnail']),
         'Amount'    => '$'.sanitizer($_POST['amount']).' CAD'
       ];
  $res = $mailer->sendEmail($fields);
  //var_dump($res);
  if ($res)
    header('Location: https://www.3ddesigncanada.com/payment_successful');
  else
    header('Location: https://www.3ddesigncanada.com/payment_declined');
} catch (\SquareConnect\ApiException $e) {
	header('Location: https://www.3ddesigncanada.com/payment_declined');
 /* For debug and future purposes
  echo "Caught exception!<br/>";
  print_r("<strong>Response body:</strong><br/>");
  echo "<pre>"; var_dump($e->getResponseBody()); echo "</pre>";
  echo "<br/><strong>Response headers:</strong><br/>";
  echo "<pre>"; var_dump($e->getResponseHeaders()); echo "</pre>";*/
}

function sanitizer($str){
  return htmlspecialchars($str, ENT_QUOTES, 'utf-8');
}
