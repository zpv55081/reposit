<?php


$razdel='admin';
$dopusk = dopusk();

if ($dopusk < 2) {
    my_mesage("У Вас недостаточно прав для доступа к разделу");
  include ("config/down.ph_");
  exit;
}

$post_selector = '';
  // формируем список должностей
if(filter_input(INPUT_POST, 'change_post') != 'Выбрать'){
  $sql_posts="SELECT `id`, `id_department`, `name` FROM `department_post` WHERE `is_deleted`='0' ORDER BY `name`";
  $query_posts=mysql_query($sql_posts,$db);
  while($postrow=mysql_fetch_assoc($query_posts)){
    $post[$postrow['id']]=$postrow;
  }
  $post_selector = '
    Должность:
    <select name="department_post" class="department_post">
    <option value="NULL">Не выбрана</option>";
    ';
  
    foreach ($post as $void => $department_post_row) {
      $post_selector .= "<option value='{$department_post_row['id']}'";
      $post_selector .= ">{$department_post_row['name']}</option>";
    }

  $post_selector .='</select>
  <input type="submit" class="button-grey" name="change_post" value="Выбрать">
  ';
}

  // формируем список компетенций по должности
$post_competencies_selector = '';
if(filter_input(INPUT_POST, 'change_post') == 'Выбрать'){
  $department_post_id = $_POST['department_post'];
  $sql_post_competencies = "SELECT
    competencies.id as competence_id,
    competencies.post_id,
    department_post.name as post_name,
    courses.name as course_name
  FROM 
    competencies
  LEFT JOIN
    department_post ON (competencies.post_id = department_post.id)
  LEFT JOIN
    courses ON (courses.id = competencies.course_id)
  WHERE
    post_id = '$department_post_id'
    AND (competencies.is_deleted = '0' OR competencies.is_deleted IS NULL)  
    AND (courses.is_deleted = '0' OR courses.is_deleted IS NULL)
    ";
  
  $query_competencies=mysql_query($sql_post_competencies, $db);
  $post_competencies_selector .= 'Компетенция:
  <select name="competencies_selector" class="competencies_selector">
    <option value="NULL">Не выбрана</option>";';
  while($competence_row=mysql_fetch_assoc($query_competencies)){
    $competencies[$competence_row['competence_id']]=$competence_row;
  }
  foreach ($competencies as $void => $competence_row) {
    $post_competencies_selector .= "<option value='{$competence_row['competence_id']}'";
    $post_competencies_selector .= ">{$competence_row['course_name']}</option>";
  }
  $post_competencies_selector .='<input type="submit" class="button-grey" name="change_competence" value="Выбрать">';

}
$table_not_certified = '';
// формируем список коллег которые не прошли или просрочен срок аттестации 
if(filter_input(INPUT_POST, 'change_competence') == 'Выбрать'){
  $competence_selected_id = $_POST['competencies_selector'];
  $table_not_certified = '
  <table id="list_table" style="text-align:center; width: 1000px;">

  <tr style="font-weight:bold;background:#AAAAAA;">
  <td>ФИО</td>
  <td>Должность</td>
  <td>Название компетенции</td>   
  <td>'.date("d.m.Y").'</td>
  </tr>
  ';

  $sql_not_certified = "SELECT
    profiles.id as profile_id,
    profiles.au_id,
    au.fio,
    profiles.department_post_id,
    department_post.name as department_post_name,
    department.name as department_name,
    competencies.id as competence_id,
    courses.name as course_name,
    passing_competencies.completion_date as passing_competencies_completion_date,
    competencies.periodicity as competence_periodicity
  FROM
    profiles
  LEFT JOIN
    au ON (au.id_au = profiles.au_id)
  LEFT JOIN 
    department ON (department.id_department = au.id_department)
  LEFT JOIN
    department_post ON (department_post.id = profiles.department_post_id)
  LEFT JOIN
    competencies ON (competencies.post_id = profiles.department_post_id)
  LEFT JOIN
    passing_competencies ON (passing_competencies.competencies_id = competencies.id  
      AND passing_competencies.au_id = profiles.au_id)
  LEFT JOIN
    courses ON (courses.id = competencies.course_id)
  LEFT JOIN 
    `group` ON (`group`.id_group = profiles.group_id)
  WHERE
    competencies.id = '$competence_selected_id'
    AND ( 
      (passing_competencies.completion_date IS NULL) 
      OR 
      (DATE_ADD(passing_competencies.completion_date, INTERVAL competencies.periodicity DAY)) < CURRENT_DATE
      )
  ORDER BY fio
  ";
  $desc_not_certified = mysql_query($sql_not_certified, $db);

  while ($row = mysql_fetch_assoc($desc_not_certified)) {
    $table_not_certified .= '
    <tr style="font-weight:bold;background:#FFFF99;">
        <td>'.$row['fio'].'</td>
        <td>'.$row['department_post_name'].'</td>
        <td>'.$row['course_name'].'</td>      
        <td><input type="checkbox" name="certif_au_competence['.$row['au_id'].']" value="'.$row['competence_id'].'"></td>
    </tr>
    ';
  }
  $table_not_certified.='
  </table>
  <input type="submit" class="button-grey" name="make_changes_certified" value="Выполнить отметки об обучении персонала">
  <br>
  ';
}

// вносим отметки об обучении в базу данных
if(filter_input(INPUT_POST, 'make_changes_certified') == 'Выполнить отметки об обучении персонала'){
  $chan_cert_auth_comp = $_POST['certif_au_competence'];
  
  $sql_chan_cert = 'INSERT
  INTO
    btu.passing_competencies (au_id,completion_date,competencies_id)
  VALUES ';

  $auth_dat_comp = [];
  foreach ($chan_cert_auth_comp as $au_id => $competence_id) {   
    $auth_dat_comp []= "($au_id, CURRENT_DATE, $competence_id)";  
  }

  $sql_chan_cert .= implode(',', $auth_dat_comp);

  $result_chan_cert = mysql_query($sql_chan_cert, $db);
  if(mysql_error()){
    $err[]="Ошибка внесения отметок: ".mysql_error();
    include("tpl/error_message.tpl.php");
    myredirect("academy.php", 5);
    die;
  } 
  if ($result_chan_cert) {
    $text = "Отметки об обучении персонала внесены";
    include("tpl/message.tpl.php");
    myredirect("academy.php", 3);
    die;
  }
}
require_once("tpl/academy/make_changes.tpl.php");
