<?php

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

$rows_completed_competencies = '';
$rows_available_competencies = '';

foreach ($employee->qualification as $competence) {
    if ($competence['completion_date'] != 0) {
    $course_name = $competence['courses_name'];
    $course_completion_date = $competence['completion_date'];
    $rows_completed_competencies .= '
    <tr style="font-weight:bold;background: linear-gradient(to top left, #daffc5 49%, #baff95 49%);">
        <td>'.$course_name.'</td>
        <td>'.$course_completion_date.'</td>
    </tr>
    ';
    } else {
    $course_name = $competence['courses_name'];
    $competence_id = $competence['competencies_id'];    
    $rows_available_competencies .= '
    <tr>
        <td style="font-weight:bold;background:#FFFF99;">'.$course_name.'</td>
        <td style="font-weight:bold;background:#FFFF99;"><input type="checkbox" name="qualif_compet" value="'.$competence_id.'"></td>
    </tr>
    ';
    }
}

require_once("tpl/profile/qualification.tpl.php");
