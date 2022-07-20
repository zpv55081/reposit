<?php

namespace Academy;

/**
 * Класс "Компетенция"
 * @author p.zajcev@k-t.org
 * @version 1.0 (12.04.2022)
 */

class Competence
{        
    /**
     * Раскрывающийся список отделов
     * @var string 
     */
    public $departments_list = '';

    /**
     * Раскрывающийся список должностей
     * @var string 
     */
    public $department_posts_list = '';

    /**
     * Раскрывающийся список навыков
     * @var string 
     */
    public $abilities_list = '';
        
    /**
     * Раскрывающийся список названий курсов
     * @var string 
     */
    public $courses_list = '';

    /**
     * Идентификаторы значений, выставляемых изначально,
     * в полях редактора текущей компетенции
     * @var string 
     */
    public $selected_ability_id = '';
    public $selected_post_id = '';
    public $selected_department_id = '';
    public $selected_course_id = '';
    public $selected_queue = '';
    public $selected_ready = '';
    public $selected_periodicity = '';
    public $selected_course_link = '';
    public $selected_description = '';
    public $selected_previous = '';


    /**
     * Задать идентификаторы значений, выставляемых изначально,
     * в полях редактора текущей компетенции
     * @param resource $db дескриптор соединения с БД
     * @param int $id_competence идентификатор текущей компетенции
     */
    public function setIdentifiersSelected($db, $id_competence)
    {
        $sql_selected_competence = "SELECT 
        department_id,
        post_id,
        ability_id,
        `queue`,
        course_id,
        `ready`,
        periodicity,
        course_link,
        `description`,
        previous 
        FROM 
        competencies
        WHERE 
        id = '$id_competence'
        ";

        $desc_selected_competence = mysql_query($sql_selected_competence, $db);

        $array_selected_competence = mysql_fetch_assoc($desc_selected_competence);

        $this->selected_ability_id = $array_selected_competence['ability_id'];
        $this->selected_post_id = $array_selected_competence['post_id'];
        $this->selected_department_id = $array_selected_competence['department_id'];
        $this->selected_course_id = $array_selected_competence['course_id'];
        $this->selected_queue = $array_selected_competence['queue'];
        ($array_selected_competence['ready'] == '1') ? $this->selected_ready = 'checked' : $this->selected_ready = '';
        $this->selected_periodicity = $array_selected_competence['periodicity'];
        $this->selected_course_link = $array_selected_competence['course_link'];
        $this->selected_description = $array_selected_competence['description'];
        $this->selected_previous = $array_selected_competence['previous'];
    }

    /**
     * Задать значения html раскрывающихся списков, с учётом
     * идентификторов значений, выставляемых изначально
     * @param int $id_selected_ability идентификтор выставляемого навыка
     * @param int $id_selected_post идентификтор выставляемой должности
     * @param int $id_selected_department идентификтор выставляемого отдела
     * @param int $id_selected_course идентификтор выставляемого курса
     * @param resource $db дескриптор соединения с БД
     */
    public function createCompetenceLists($db, $id_competence = '')
    { 
        $this->setIdentifiersSelected($db, $id_competence);

        $this->abilities_list = $this->createListFromTable('abilities', $db, 'id', 'name', $this->selected_ability_id);
        $this->department_posts_list = $this->createListFromTable('department_post', $db, 'id', 'name', $this->selected_post_id);
        $this->departments_list = $this->createListFromTable('department', $db, 'id_department', 'name', $this->selected_department_id);
        $this->courses_list = $this->createListFromTable('courses', $db, 'id', 'name', $this->selected_course_id);
    }

    /**
     * Сделать раскрывающийся список из таблицы
     * @param string $table_title название таблицы и будущего селектора
     * @param string $heading_id по умолчанию = 'id'
     * @param string $heading_name по умолчанию = 'name'
     * @param resource $db дескриптор соединения с БД
     * @return string html раскрывающегося списка 
     */
    public function createListFromTable($table_title, $db, $heading_id = 'id', $heading_name = 'name', $id_selected = '')
    {
        $table_assoc_array = $this->getIdNameTableRows($table_title, $db, $heading_id, $heading_name);
        
        $drop_down_list_string = $this->createDropdownList($table_assoc_array, $table_title, $id_selected);

        return $drop_down_list_string;
    }    

    /**
     * Получить неудалённые строки sql таблицы `id`/`name`
     * Сортировка в порядке `name`
     * @param string $table_name название таблицы
     * @param string $heading_id по умолчанию = 'id'
     * @param string $heading_name по умолчанию = 'name'
     * @param resource $db дескриптор соединения с БД
     * @return array ассоциативный массив id => name
     */
    public function getIdNameTableRows($table_name, $db, $heading_id = 'id', $heading_name = 'name')
    {
        $sql_get_table = "SELECT *
        FROM 
            `$table_name`
        WHERE
            `is_deleted` = '0'
            OR `is_deleted` IS NULL
        ORDER BY
            `name`
        ";

        $desc_get_table = mysql_query($sql_get_table, $db);

        while($row = mysql_fetch_assoc($desc_get_table)){   
            $table_assoc_array [$row[$heading_id]] = $row[$heading_name];
        }

        return $table_assoc_array;
    }

    /**
     * Сформировать раскрывающийся список со значениями name из массива 
     * @param array $table_assoc_array
     * @param string $title заголовок
     * @param int $id_selected идентификтор значения в списке, выставляемого изначально
     * @return string html раскрывающегося списка
     */
    public function createDropdownList($table_assoc_array, $title, $id_selected = '')
    {   
        $drop_down_list_string = "<select name=\"{$title}\" id=\"{$title}\">";
        $drop_down_list_string .= "<option value=\"0\">--</option>";

        foreach ($table_assoc_array as $id => $position) {
            if ($id_selected == $id) {
                $setter = 'selected';
            } else {
                $setter = '';
            }
            $drop_down_list_string .= "<option {$setter} value=\"{$id}\">{$position}</option>";
        }
        $drop_down_list_string .= "</select>";

        return $drop_down_list_string;
    }  

    /**
     * Добавить в БД новую компетенцию по значениям формы выбора
     * @param resource $db дескриптор соединения с БД
     * @param array $option_values значения формы выбора
     * @return bool возвращает true в случае успешного добавления в базу
     * В случае ошибки выводит в браузер сообщение об ошибке, 
     * запускает редирект на academy.php и останавливает текущий скрипт
     */
    public static function addCompetenceToDb($option_values, $db){

        if (!$option_values['ready']) 
            $option_values['ready'] = 'NULL';

        $sql_add_competence = "INSERT
        INTO
            btu.competencies (department_id, post_id, ability_id, `queue`, course_id, `description`, ready, periodicity, course_link, created_at, setter_login)
        VALUES
            (
            NULL,
            {$option_values['department_post']},
            {$option_values['abilities']},
            {$option_values['que']},
            {$option_values['courses']},
            '{$option_values['description']}',
            {$option_values['ready']},
            {$option_values['periodicity']},
            '{$option_values['course_link']}',
            CURRENT_TIMESTAMP,
            '{$_SESSION['user']}'
            )
        ";

        $query = mysql_query($sql_add_competence, $db);

        if(mysql_error()){
            $err[]="Ошибка добавления компетенции: ".mysql_error();
            include("tpl/error_message.tpl.php");
            myredirect("academy.php", 5);
            die;
          }
        
        return $query;   
    }

    /**
     * Пометить в БД компетенцию как is_deleted = "1"
     * @param resource $db дескриптор соединения с БД
     * @param int $id_to_delete идентификатор компетенции, помечаемой как is_deleted
     * @return bool возвращает true в случае успешной пометки is_deleted
     * В случае ошибки выводит в браузер сообщение об ошибке, 
     * запускает редирект на academy.php и останавливает текущий скрипт
     */
    public static function deleteCompetenceFromDb($id_to_delete, $db){

        $sql_delete_competence = "UPDATE 
                btu.competencies
            SET 
                is_deleted = 1,
                deleted_at=CURRENT_TIMESTAMP,
                setter_login='{$_SESSION['user']}'
            WHERE
                id = '$id_to_delete'
        ";

        $query = mysql_query($sql_delete_competence, $db);

        if(mysql_error()){
            $err[]="Ошибка удаления компетенции: ".mysql_error();
            include("tpl/error_message.tpl.php");
            myredirect("academy.php", 5);
            die;
        }
        
        return $query;   
    }

    /**
     * Внести изменения в строку компетенции в БД
     * @param resource $db дескриптор соединения с БД
     * @param int $values_to_modification 
     * @return bool возвращает true в случае успешного внесения изменений
     * В случае ошибки выводит в браузер сообщение об ошибке, 
     * запускает редирект на academy.php и останавливает текущий скрипт
     */
    public static function modifyCompetenceInDb($values_to_modification, $db){
        $modifiable_id =  $values_to_modification['modifiable_id'];
        $department_post = $values_to_modification['department_post'];
        $abilities = $values_to_modification['abilities'];
        $que = $values_to_modification['que'];
        $periodicity = $values_to_modification['periodicity'];
        $course_link = $values_to_modification['course_link'];
        $description = $values_to_modification['description'];
        $previous = $values_to_modification['previous'];
        if (isset($values_to_modification['ready'])) {
            $ready = $values_to_modification['ready'];
        } else {
            $ready = 'NULL';
        }

        $sql_modify_competence = "UPDATE 
                btu.competencies
            SET
                post_id='$department_post',
                ability_id='$abilities',
                course_link='$course_link',
                `description` = '$description',
                `previous` = '$previous',
                periodicity='$periodicity',
                `queue`='$que',
                ready='$ready',
                updated_at=CURRENT_TIMESTAMP,
                setter_login='{$_SESSION['user']}'
            WHERE
                id = '$modifiable_id'
        ";

        $query = mysql_query($sql_modify_competence, $db);

        if(mysql_error()){
            $err[]="Ошибка изменения компетенции: ".mysql_error();
            include("tpl/error_message.tpl.php");
            myredirect("academy.php", 5);
            die;
        }
        
        return $query;   
    }
}
