<?php

//   ini_set('error_reporting', E_ALL);
//   ini_set('display_errors', 1);
//   ini_set('display_startup_errors', 1);

require_once('config/co.php');
require_once('config/function.ph_');
require_once("config/top.ph_");
require_once('objects/Profile/Employee.php');

use Profile\Employee;
use Profile\Territory;

$razdel='admin';
$dopusk = dopusk();

if(!filter_input(INPUT_GET, 'id')) {
  $_GET['id'] = intval($_SESSION['user_id']);
}
else {
  $_GET['id'] = intval($_GET['id']);
}

if ($dopusk < 1) {
  my_mesage("У Вас недостаточно прав для доступа к разделу");
include ("config/down.ph_");
exit;
}

if ($dopusk < 2 AND $_GET['id'] != $_SESSION['user_id']) {
    my_mesage("У Вас недостаточно прав для доступа к разделу");
  include ("config/down.ph_");
  exit;
}

if (isset ($_GET['page'])){
  $get_page = $_GET['page'];
} else {
  $get_page = '';
}
switch ($get_page) {
  case 'view':
    die('Страницы ещё нет');
    break;    
  case 'qualification':
    $employee = new Employee ($_GET['id'], $db);
    require_once("inc/profile/qualification.inc.php");
    break;
  case 'organizations':
    require_once("inc/profile/organizations.inc.php");
    break;      
  default:
    $employee = new Employee ($_GET['id'], $db);
    $territ = new Territory();
    $names_city_cluster = $territ->decodeNames($employee->cities, $db);
    require_once("inc/profile/profile.inc.php");
    break;
}

require_once("config/down.ph_");

