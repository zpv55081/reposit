<?php

$razdel='admin';
$dopusk = dopusk();

if ($dopusk < 2) {
    my_mesage("У Вас недостаточно прав для доступа к разделу");
  include ("config/down.ph_");
  exit;
}

use Academy\Competence;

$id_competence = $_GET['id_competence'];

$editor_competence = new Competence;
$editor_competence->createCompetenceLists($db, $id_competence);

$sql_all_competencies = "SELECT
  competencies.id,
	department.name as department,
	department_post.name as post,
	abilities.name as ability,
  abilities.id as ability_id,
	competencies.queue,
  competencies.course_link,
	courses.name as course,
  competencies.description,
  competencies.fixed
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

$editable_and_another_competencies_html_rows = '';
while ($row_competence = mysql_fetch_assoc($desc_all_competencies)) {
  if ($id_competence != $row_competence['id']) {
    $del_comp = " <a href = \"?page=del_competence&id_competence={$row_competence['id']}\" "
      . "onclick = \"JavaScript:if(confirm('Вы действительно хотите удалить компетенцию `{$row_competence['course']}` для этого навыка?'))
        {return true;}else{return false;};\">"
      . "<img src = \"img/b_drop.png\" title = \"Удалить компетенцию\"></a> ";
    $edit_comp = '<a href = "?page=edit_competence&id_competence=' . $row_competence['id'] . '">
    <img src = "img/b_edit.png" title = "Редактировать компетенцию"></a>';
    $editable_and_another_competencies_html_rows .= '
    <tr style="font-weight:bold;background:#FFFF99;">
        <td>' . $row_competence['post'] . '</td>
        <td>' . $row_competence['ability'] . '</td>
        <td>' . $row_competence['queue'] . '</td>
        <td><a href="' . $row_competence['course_link'] . '" >' . $row_competence['course'] . '</a></td>
        <td>' . $row_competence['description'] . '</td>
        <td>' . $edit_comp . '' . $del_comp . ' 
        </td>
    </tr>
    ';
    //рабочая строка редактора
  } else {
    //если параметры компетенции не зафиксированы, то предлагаем редактировать в т.ч. её ключевые элементы
    if($row_competence['fixed'] == null) {
      $editable_and_another_competencies_html_rows .= '
      <tr style="font-weight:bold;background:#AAAAAA;">
        <td>' . $editor_competence->department_posts_list . '</td>
        <td>' .$editor_competence->abilities_list . '</td>
        <td><input type="number" min="0" max="99" step="0.1" name="que" size="5" style="width:50px;" value="' .$editor_competence->selected_queue. '" required> </td>
        <td>' . $row_competence['course'] . '</td>
        <td>пред.№оч.=<input type="text" name="previous" id="previous" value="' .$editor_competence->selected_previous. '" style="width:70px;" title="если несколько, то через точку с запятой">
        <input type="text" name="description" id="description" value="' .$editor_competence->selected_description. '" style="width:300px;"></td>
        <td rowspan="2">
            <input type="hidden" name="modifiable_id" value="'.$id_competence.'" />
            <input type="hidden" name="page" value="save_modification" />
            <button type="submit">Сохранить<br>изменения</button>
        </td>
      </tr>
      <tr style="font-weight:bold;background:#AAAAAA;">
        <td colspan="5">
            Периодичность курса в днях:
            <input type="number" min="0" name="periodicity" value="' .$editor_competence->selected_periodicity. '" style="width:60px;">
            Ссылка на учебный курс:
            <input type="text" name="course_link" id="course_link" value="' .$editor_competence->selected_course_link. '" style="width:380px;">
            Готовность курса
            <input type="checkbox" name="ready" value="1" ' . $editor_competence->selected_ready . '>
        </td>
      </tr>
    ';
    } else {
      // НЕ предлагаем редактировать ключевые элементы (для отображения "деактивированное поле", для отправки значения в форме "скрытое")
      $editable_and_another_competencies_html_rows .= '
      <tr style="font-weight:bold;background:#AAAAAA;">
        <td>' . $editor_competence->department_posts_list . '</td>
        <td>' . $row_competence['ability'] . '<input type="hidden" name="abilities" id="abilities" value="' .$row_competence['ability_id']. '"></td>
        <td>
        <input type="number" min="0" max="99" step="0.1" name="que" size="5" style="width:50px;" value="' .$editor_competence->selected_queue. '" required disabled> 
        <input type="hidden" name="que" value="' .$editor_competence->selected_queue. '">
        </td>
        <td>' . $row_competence['course'] . '</td>
        <td>пред.№оч.=
        <input type="text" name="previous" id="previous" value="' .$editor_competence->selected_previous. '" style="width:70px;" title="если несколько, то через точку с запятой" disabled>
        <input type="hidden" name="previous" id="previous" value="' .$editor_competence->selected_previous. '">
        <input type="text" name="description" id="description" value="' .$editor_competence->selected_description. '" style="width:300px;"></td>
        <td rowspan="2">
            <input type="hidden" name="modifiable_id" value="'.$id_competence.'" />
            <input type="hidden" name="page" value="save_modification" />
            <button type="submit">Сохранить<br>изменения</button>
        </td>
      </tr>
      <tr style="font-weight:bold;background:#AAAAAA;">
        <td colspan="5">
            Периодичность курса в днях:
            <input type="number" min="0" name="periodicity" value="' .$editor_competence->selected_periodicity. '" style="width:60px;">
            Ссылка на учебный курс:
            <input type="text" name="course_link" id="course_link" value="' .$editor_competence->selected_course_link. '" style="width:380px;">
            Готовность курса
            <input type="checkbox" name="ready" value="1" ' . $editor_competence->selected_ready . '>
        </td>
      </tr>
    ';
    }   
  }
}

require_once("tpl/academy/edit_competence.tpl.php");
