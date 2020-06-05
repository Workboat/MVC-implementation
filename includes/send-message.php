<?php
require_once 'OrderNotifier.php';

$mailer  = new OrderNotifier;
/*
$fields  = [
       'Name'      => sanitizer($_POST['name']),
       'Email'     => sanitizer($_POST['email']),
       'Phone'     => sanitizer($_POST['phone']),
       'Size x'    => sanitizer($_POST['sizex']),
       'Size y'    => sanitizer($_POST['sizey']),
       'Message'   => sanitizer($_POST['message']),
       'Thumbnail' => urlencode($_POST['thumbnail']),
       'Amount'    => '$'.sanitizer($_POST['amount']).' CAD'
   ];*/
$res = $mailer->sendEmail($_GET);
//var_dump($_GET);