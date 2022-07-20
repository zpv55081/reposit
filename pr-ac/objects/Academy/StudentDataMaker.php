<?php

namespace Academy;

/**
 * Класс "Формирователь сведений об обучаемых"
 * @author p.zajcev@k-t.org
 * @version 1.0 (22.04.2022)
 */

class StudentDataMaker
{
    /**
     * @var array соответствующие должности профили с компетенциями
     */
    public $post_profiles_competencies = [];

    /**
     * Определить по должности профили с компетенциями
     * @param resource $db дескриптор соединения с БД
     * @param int $department_post_id идентификтор должности
     * @param string $sort_by способ сортировки
     * @return void
     */
    public function choosePostProfilesCompetencies($db, $department_post_id, $sort_by)
    {
        $sql_profile_competencies = "SELECT
        profiles.id as profile_id,
        profiles.au_id,
        au.fio,
	    au.phone,
        profiles.department_post_id,
        department_post.name as department_post_name,
        department.name as department_name,
        profiles.date_added as profile_date_added,
        `group`.name as profile_placement_name,
        competencies.id as competence_id,
	    competencies.periodicity as competence_periodicity, 
        courses.name as course_name,
        passing_competencies.completion_date as passing_competencies_completion_date
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
            profiles.department_post_id = '$department_post_id'
        ORDER BY $sort_by, fio
        ";

        $desc_profile_competencies = mysql_query($sql_profile_competencies, $db);

        while ($row = mysql_fetch_assoc($desc_profile_competencies)) {
            $this->post_profiles_competencies [] = $row;
        }

        return null;
    }
}
