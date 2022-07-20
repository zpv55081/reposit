<?php

$razdel='admin';
$dopusk = dopusk();

if ($dopusk < 2) {
    my_mesage("У Вас недостаточно прав для доступа к разделу");
  include ("config/down.ph_");
  exit;
}

if(filter_input(INPUT_POST, 'add_ability') == 'Добавить'){

  $ability = $_POST['ability'];

  $insert_ability_sql = "INSERT 
  INTO
    btu.abilities (name)
	VALUES
    ('$ability')
  ";

  $query = mysql_query($insert_ability_sql, $db);

  if(mysql_error()){
    $err[]="Ошибка добавления: ".mysql_error();
    include("tpl/error_message.tpl.php");
  } else{
     $text="Навык добавлен";
    include("tpl/message.tpl.php");
  }
  
}

require_once("tpl/academy/abilities.tpl.php");
