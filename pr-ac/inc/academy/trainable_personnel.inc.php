<?php

require_once('objects/Academy/StudentDataMaker.php');

use Academy\StudentDataMaker;

$razdel='admin';
$dopusk = dopusk();

if ($dopusk < 2) {
    my_mesage("У Вас недостаточно прав для доступа к разделу");
  include ("config/down.ph_");
  exit;
}

// Список должностей
$sql="SELECT `id`, `id_department`, `name` FROM `department_post` WHERE `is_deleted`='0' ORDER BY `name`";
$query=mysql_query($sql,$db);
while($postrow=mysql_fetch_assoc($query)){
  $post[$postrow['id']]=$postrow;
}

$rows_trainable_personnel = '';
if(filter_input(INPUT_GET, 'generate') == 'Сформировать'){
  $department_post_id = $_GET['department_post'];
  $sort_by = $_GET['sort_by'];
  $rows_trainable_personnel = '
    <tr style="font-weight:bold;background:#AAAAAA;">
    <td title="Дата добавления сотрудника в `profiles`">Дата</td>
    <td>Город (трудоустройства)</td>
    <td>ФИО</td>
    <td>Телефон</td>
    <td>Отдел</td>
    <td>Должность</td>
    <td>Компетенции</td>
    </tr>
  ';
  $tr_pers = new StudentDataMaker;
  $tr_pers->choosePostProfilesCompetencies($db, $department_post_id, $sort_by);
  foreach ($tr_pers->post_profiles_competencies as $prof_compet) {
    // Если отметка о дате прохождения курса == NULL, то выводим строку как нужную
    if ($prof_compet['passing_competencies_completion_date'] == NULL) {
      $rows_trainable_personnel .= '
        <tr style="font-weight:bold;background:#FFFF99;">
        <td>'.$prof_compet['profile_date_added'].'</td>
        <td>'.$prof_compet['profile_placement_name'].'</td>
        <td>'.$prof_compet['fio'].'</td>
        <td>'.$prof_compet['phone'].'</td>
        <td>'.$prof_compet['department_name'].'</td>
        <td>'.$prof_compet['department_post_name'].'</td>
        <td>'.$prof_compet['course_name'].'</td>
        </tr>
      ';
    }  
  }
}

require_once("tpl/academy/trainable_personnel.tpl.php");
