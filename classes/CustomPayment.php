<?php
namespace Parser\Wallpapers;

require 'vendor/autoload.php';

class CustomPayment extends \SquareConnect\Api\PaymentsApi {
  public function getPayment($id = null){
    return $this->payment;
  }
}