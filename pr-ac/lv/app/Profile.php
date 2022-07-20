<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Profile extends Model {

	public $timestamps = false;

	/**
	 * Отобрать сотрудников по фильтрам
	 * @param resource $db дескриптор  БД
	 * @param $profile_addition_date_start фильтр начальной даты
	 * @param $profile_addition_date_finish фильтр конечной даты
	 * @param $AND_profiles_group_id фильтр города трудоустроенности
	 * @param $AND_profiles_department_post_id фильтр должности
	 * @return array|null
	 */
	public static function selectFiltered($db,
	$profile_addition_date_start,
	$profile_addition_date_finish,
	$AND_profiles_group_id,
	$AND_profiles_department_post_id
	)
	{
		$sql_select_filtered = "SELECT 
			profiles.date_added,
			profiles.id as profile_id,
			profiles.au_id,
			au.fio,
			au.email,
			au.phone,
			`group`.name as city_placement,
			department_post.name as department_post
		FROM 
			profiles
		LEFT JOIN
			au ON (au.id_au = profiles.au_id)
		LEFT JOIN 
			`group` ON (`group`.id_group = profiles.group_id)
		LEFT JOIN
			department_post ON (department_post.id = profiles.department_post_id)
		WHERE			
			(profiles.date_added >= '$profile_addition_date_start')
			AND
			(profiles.date_added <= '$profile_addition_date_finish') $AND_profiles_group_id $AND_profiles_department_post_id
		ORDER BY
			au.fio
		";

		$result_select_filtered = mysql_query($sql_select_filtered, $db);

		while ($profile = mysql_fetch_assoc($result_select_filtered)) {
			$profiles_assigned_abilities_array [] = $profile;
		}
		
		if (isset($profiles_assigned_abilities_array)) {
			return $profiles_assigned_abilities_array;
		} else 
			return NULL;
	}

	/**
	 * Отобрать сотрудников с их уже 
	 * назначенными способностями
	 * @param resource $db дескриптор  БД
	 * @param $profile_addition_date_start фильтр начальной даты
	 * @param $profile_addition_date_finish фильтр конечной даты
	 * @param $AND_profiles_group_id фильтр города трудоустроенности
	 * @param $AND_profiles_department_post_id фильтр должности
	 * @return array|null
	 */
	public static function selectAssignedAbilities($db,
	$profile_addition_date_start,
	$profile_addition_date_finish,
	$AND_profiles_group_id,
	$AND_profiles_department_post_id
	)
	{
		$sql_select_assigned_abilities = "SELECT 
			profiles.date_added,
			profiles.id as profile_id,
			profiles.au_id,
			au.fio,
			`group`.name as city_placement,
			department_post.name as department_post,
			assigned_abilities.abilities_id as assigned_ability_id,
			abilities.name as ability
		FROM 
			profiles
		LEFT JOIN
			au ON (au.id_au = profiles.au_id)
		LEFT JOIN 
			`group` ON (`group`.id_group = profiles.group_id)
		LEFT JOIN
			department_post ON (department_post.id = profiles.department_post_id)
		LEFT JOIN
			assigned_abilities ON (assigned_abilities.au_id = profiles.au_id)
		LEFT JOIN 
			abilities ON (abilities.id = assigned_abilities.abilities_id)
		WHERE
			(assigned_abilities.is_deleted IS NULL)
			AND
			(profiles.date_added >= '$profile_addition_date_start')
			AND
			(profiles.date_added <= '$profile_addition_date_finish') $AND_profiles_group_id $AND_profiles_department_post_id
		ORDER BY
			au.fio, abilities.name
		";

		$result_select_assigned_abilities = mysql_query($sql_select_assigned_abilities, $db);

		$current_profile = '';
		while ($assigned = mysql_fetch_assoc($result_select_assigned_abilities)) {
			if ($current_profile !== $assigned['profile_id']) {
				$profiles_assigned_abilities_array[$assigned['profile_id']] = 
				['date_added' => $assigned['date_added'],
				'profile_id' => $assigned['profile_id'],
				'au_id' => $assigned['au_id'],
				'fio' => $assigned['fio'],
				'city_placement' => $assigned['city_placement'],
				'department_post' => $assigned['department_post'],
				'assigned_abilities_ids' => [$assigned['assigned_ability_id']]
				];
				$current_profile = $assigned['profile_id'];
			} else {
				$profiles_assigned_abilities_array[$assigned['profile_id']] ['assigned_abilities_ids'][] = $assigned['assigned_ability_id'];
			}
		}
		
		if (isset($profiles_assigned_abilities_array)) {
			return $profiles_assigned_abilities_array;
		} else 
			return NULL;
	}
}
