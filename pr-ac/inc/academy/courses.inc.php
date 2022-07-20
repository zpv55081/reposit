<?php

$razdel='admin';
$dopusk = dopusk();

if ($dopusk < 2) {
    my_mesage("У Вас недостаточно прав для доступа к разделу");
  include ("config/down.ph_");
  exit;
}

if(filter_input(INPUT_POST, 'add_course') == 'Добавить'){

  $course = $_POST['course'];

  $insert_course_sql = "INSERT
  INTO
    btu.courses (name)
	VALUES
    ('$course')
  ";

  $query = mysql_query($insert_course_sql, $db);

  if(mysql_error()){
    $err[]="Ошибка добавления: ".mysql_error();
    include("tpl/error_message.tpl.php");
  } else{
     $text="Наименование компетенции добавлено";
    include("tpl/message.tpl.php");
  } 
}

require_once("tpl/academy/courses.tpl.php");
