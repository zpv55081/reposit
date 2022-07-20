<?php

$razdel='admin';
$dopusk = dopusk();

if ($dopusk < 2) {
    my_mesage("У Вас недостаточно прав для доступа к разделу");
  include ("config/down.ph_");
  exit;
}

$sql_all_competencies = "SELECT
    competencies.id,
	department.name as department,
	department_post.name as post,
	abilities.name as ability,
	competencies.queue,
    competencies.course_link,
	courses.name as course,
    competencies.description
FROM 
	competencies
LEFT JOIN
	department ON department.id_department = competencies.department_id
LEFT JOIN
	department_post ON department_post.id = competencies.post_id
LEFT JOIN
	abilities ON abilities.id = competencies.ability_id
LEFT JOIN
	courses ON courses.id = competencies.course_id
WHERE
    competencies.is_deleted = '0' 
    OR competencies.is_deleted IS NULL
ORDER BY
	queue, department, post, ability, course
";
$desc_all_competencies = mysql_query($sql_all_competencies, $db);
$all_competencies_html_rows = '';
while ($row_competence = mysql_fetch_assoc($desc_all_competencies)) {
    $del_comp = " <a href = \"?page=del_competence&id_competence={$row_competence['id']}\" "
        . "onclick = \"JavaScript:if(confirm('Вы действительно хотите удалить компетенцию `{$row_competence['course']}` для этого навыка?'))
        {return true;}else{return false;};\">"
        . "<img src = \"img/b_drop.png\" title = \"Удалить компетенцию\"></a> ";
    $edit_comp = '<a href = "?page=edit_competence&id_competence=' . $row_competence['id'] . '">
    <img src = "img/b_edit.png" title = "Редактировать компетенцию"></a>';
    $all_competencies_html_rows .= '
    <tr style="font-weight:bold;background:#FFFF99;">
        <td>' . $row_competence['post'] . '</td>
        <td>' . $row_competence['ability'] . '</td>
        <td style="text-transform: uppercase;">'. $row_competence['queue'] . '</td>
        <td><a href="' . $row_competence['course_link'] . '" >' . $row_competence['course'] . '</a></td>
        <td>' . $row_competence['description'] . '</td>
        <td>' . $edit_comp . '' . $del_comp . ' 
        </td>
    </tr>
    ';
}
require_once("tpl/academy/academy.tpl.php");
