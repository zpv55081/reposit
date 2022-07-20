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

$uniq_cluster_name = '';
$clusters_html_table = '';
$cities_html_table = '';

foreach ($names_city_cluster as $id ) {
    if ($uniq_cluster_name != $id['cluster_name']){
        $uniq_cluster_name = $id['cluster_name'];
        $clusters_html_table .= "
            <tr>
            <td style='font-weight:bold;background:#f9c66a;'>{$id['cluster_name']}</td>
            </tr>
            ";
      }
}

foreach ($names_city_cluster as $id ) {
     $cities_html_table .= "
    <tr>
    <td style='font-weight:bold;background:#f9c66a;'>{$id['city_name']}</td>
    </tr>
    ";
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
        <td colspan="2" style="font-weight:bold;background:#FFFF99;">'.$course_name.'</td>        
    </tr>
    ';
    }
}

require_once("tpl/profile/profile.tpl.php");
