<?php
//namespace Parser\Wallpapers;

// Uncomment for debug
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'vendor/autoload.php';
require_once 'settings/env.php';

//$parser = new WallpaperParser($_PARAMS);
/*$action = null;
$page   = null;
$item   = null;

if (!empty($_GET['page']))
  $page = $_GET['page'];
if (!empty($_GET['item']))
  $item = $_GET['item'];
if (!empty($_GET['action']))
  $action = $_GET['action'];
*/  
$router = new Router();
