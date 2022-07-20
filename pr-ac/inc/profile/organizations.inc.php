<?php

$razdel='admin';
$dopusk = dopusk();

if ($dopusk < 4) {
    my_mesage("У Вас недостаточно прав для доступа к разделу");
  include ("config/down.ph_");
  exit;
}

if(filter_input(INPUT_POST, 'add_organization') == 'Добавить'){

  $organization = $_POST['organization'];

  $insert_organization_sql = "INSERT 
  INTO
    btu.organizations (name)
	VALUES
    ('$organization')
  ";

  $query = mysql_query($insert_organization_sql, $db);

  if(mysql_error()){
    $err[]="Ошибка добавления: ".mysql_error();
    include("tpl/error_message.tpl.php");
  } else{
     $text="Наименование организации добавлено";
    include("tpl/message.tpl.php");
  }
  
}

require_once("tpl/profile/organizations.tpl.php");
