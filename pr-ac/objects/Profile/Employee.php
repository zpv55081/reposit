<?php

namespace Profile;

/**
 * Класс "Сотрудник"
 * @author p.zajcev@k-t.org
 * @version 1.0 (05.04.2022)
 */

class Employee
{
    public $id;
    public $login;
    public $fio;
    public $email;
    public $phone;
    public $organization;
    public $id_department;
    public $department_post;
    public $date_added;
    public $cities = [];
    public $qualification = [];

     /**
     * Определение значения id, логина, ФИО, email, телефона, организации,
     * должности, даты добавления к системе профайлов, городов доступа
     * для запрашиваемого сотрудиника
     * @param int $id_request идентификатор запрашиваемого сотрудника
     * @param resource $db дескриптор соединения с БД
     */
    public function __construct($id_request, $db)
    {
        $sql_employee = "SELECT
            au.id_au,
            au.fio,
            au.email,
            au.phone,
            au.group_list,
            au.`login`,
            au.id_department,
            profiles.department_post_id,
            department_post.name as department_post,
            organizations.name as organization, 
            profiles.date_added 
        FROM
            au
        LEFT JOIN 
            profiles ON (profiles.au_id = au.id_au)
        LEFT JOIN
            department_post ON (department_post.id = profiles.department_post_id)
        LEFT JOIN 
            organizations ON (organizations.id = profiles.organizations_id)
        WHERE 
            id_au = $id_request
        ";

        $desc_sql_employee = mysql_query($sql_employee, $db);

        while ($row_employee = mysql_fetch_assoc($desc_sql_employee)) {

            $this->id = $row_employee['id_au'];

            $this->login = $row_employee['login'];

            $this->fio = $row_employee['fio'];

            $this->email = $row_employee['email'];

            $this->phone = $row_employee['phone'];

            $this->organization = $row_employee['organization'];

            $this->department_post = $row_employee['department_post'];

            $this->department_post_id = $row_employee['department_post_id'] ? $row_employee['department_post_id']: 0;

            $this->id_department = $row_employee['id_department'] ? $row_employee['id_department'] : 0;

            $this->date_added = $row_employee['date_added'];

            $this->cities = explode(',', $row_employee['group_list']);
        }

        $this->setQualification($this->id_department, $this->department_post_id, $db);

    }

    /**
     * Определение списка компетенеций
     * @param int $department_id идентификатор отдела
     * @param int $post_id идентификатор должности
     * @param resource $db дескриптор соединения с БД
     */
    public function setQualification($department_id, $post_id, $db)
    {
        $sql_employee_competencies = "SELECT
            competencies.id as competencies_id,
            competencies.department_id,
            department.name as department_name, 
            competencies.post_id,
            department_post.name as department_post_name,
            competencies.ability_id,
            abilities.name as abilities_name,
            competencies.queue,
            competencies.course_id,
            courses.name as courses_name,
            competencies.ready,
            competencies.periodicity,
            competencies.course_link,
            passing_competencies.id as passing_competencies_id,
            passing_competencies.au_id,
            passing_competencies.begin_date,
            passing_competencies.completion_date,
            passing_competencies.completion_file
        FROM
            competencies
        LEFT JOIN 
            department ON (department.id_department = competencies.department_id)
        LEFT JOIN 
            department_post ON (department_post.id = competencies.post_id)
        LEFT JOIN 
            abilities ON (abilities.id = competencies.ability_id)
        LEFT JOIN 
            courses ON (courses.id = competencies.course_id)
        LEFT JOIN 
            passing_competencies ON (passing_competencies.competencies_id = competencies.id)
        WHERE
-- т.к. решили убрать отделы               competencies.department_id = $department_id
-- и            AND 
-- должности               competencies.post_id = $post_id
-- список нужно будет фильтровать по другому           AND 
                (competencies.`is_deleted` = '0' OR competencies.`is_deleted` is null)
            AND 
                (passing_competencies.`is_deleted` = '0' OR competencies.`is_deleted` is null)
        ORDER BY
            department_name, department_post_name, abilities_name, queue, courses_name
        ";
        
        $desc_employee_competencies = mysql_query($sql_employee_competencies, $db);

        $qualif = [];

        while ($row = mysql_fetch_assoc($desc_employee_competencies)){
            $qualif [$row['competencies_id']] = $row; 
        }
        $this->qualification = $qualif;
    }
}
