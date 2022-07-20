<?php

//   ini_set('error_reporting', E_ALL);
//   ini_set('display_errors', 1);
//   ini_set('display_startup_errors', 1);

require_once('config/co.php');
require_once('config/function.ph_');
require_once("config/top.ph_");
require_once('objects/Academy/Competence.php');

use Academy\Competence;

$razdel='admin';
$dopusk = dopusk();

if ($dopusk < 2) {
    my_mesage("У Вас недостаточно прав для доступа к разделу");
  include ("config/down.ph_");
  exit;
}

if (isset($_REQUEST['page'])){
  $request_page = $_REQUEST['page'];
} else {
  $request_page = '';
}
switch ($request_page) {
  case 'view':
    die('Страницы ещё нет');
    break;
  case 'as_co_sending_emails':
    require_once 'inc/academy/as_co_sending_emails.inc.php';
    break;
  case 'make_changes':
    require_once("inc/academy/make_changes.inc.php");
    break; 
  case 'trainable_personnel':
    require_once("inc/academy/trainable_personnel.inc.php");
    break;
  case 'abilities':
    require_once("inc/academy/abilities.inc.php");
    break;    
  case 'courses':
    require_once("inc/academy/courses.inc.php");
    break;    
  case 'edit_competence':
    require_once("inc/academy/edit_competence.inc.php");
    break;
  case 'save_modification':
    $values_to_modification = $_POST;
    $modificationCompetence = Competence::modifyCompetenceInDb($values_to_modification, $db);
    if($modificationCompetence){
      $text = "Изменения компетенции сохранены";
      include("tpl/message.tpl.php");
      myredirect("academy.php", 1);
    }    
    break;         
  case 'del_competence':
    $id_to_delete = $_GET['id_competence'];
    $delCompetence = Competence::deleteCompetenceFromDb($id_to_delete, $db);
    if($delCompetence){
      $text = "Компетенция удалена";
      include("tpl/message.tpl.php");
      myredirect("academy.php", 1);
    }    
    break;         
  case 'add_competence':
    $option_values = $_POST;
    $addCompetence = Competence::addCompetenceToDb($option_values, $db);
    if($addCompetence){
      $text = "Компетенция добавлена в базу";
      include("tpl/message.tpl.php");
      myredirect("academy.php", 1);
    }    
    break;       
  default:
  $maker_competence = new Competence;
  $maker_competence->createCompetenceLists($db);
  require_once("inc/academy/academy.inc.php");
    break;
}
require_once("config/down.ph_");
