<?php namespace App\Http\Controllers;

use App\Ability;
use App\DepartmentPost;
use App\Http\Controllers\Controller;
use App\Profile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChooseAbilitiesController extends Controller {

	public function profilesFiltersDisplay() 
	{
		require_once '../t_b/adapt.php';
		
		$razdel='admin';
		require 'lv/t_b/adapt_dostup.php';
		if ($dopusk < 2) {
			my_mesage("У Вас недостаточно прав для доступа к разделу");
		exit;
		}

		return view('academy.choose_profiles_filters')->with([
			'headline' => 'ВЫБОР СПОСОБНОСТЕЙ (ТИПОВ РАБОТ) ДЛЯ СОТРУДНИКОВ',
			'form_action' => 'profiles_filters_submit',
			'posts' => DepartmentPost::selectPosts($db),
			'arrgroup' => $arrgroup
			]);
	}

	public function profilesFiltersSubmit(Request $request)
	{
		require_once '../t_b/adapt.php';
		
		$razdel='admin';
		require 'lv/t_b/adapt_dostup.php';
		if ($dopusk < 2) {
			my_mesage("У Вас недостаточно прав для доступа к разделу");
		exit;
		}

		//на случай пустого ввода начальной даты
		if ($request->profile_addition_date_start == "") {
			$profile_addition_date_start = '1900-01-01';
		} else {
			$profile_addition_date_start = $request->profile_addition_date_start;	
		}
		//на случай пустого ввода конечной даты
		if ($request->profile_addition_date_finish == "") {
			$profile_addition_date_finish = '2100-01-01';
		} else {
			$profile_addition_date_finish = "$request->profile_addition_date_finish 23:59:59";	
		}
		//на случаи игнора или отсутствия города трудоустроенности
		$city_placement_id = $request->city_placement_id;
		if ($city_placement_id === 'ignore') {
			$AND_profiles_group_id = '';
		} else if ($city_placement_id === 'NULL') {
			$AND_profiles_group_id = ' AND (profiles.group_id IS NULL)';
		} else {
			$AND_profiles_group_id = ' AND (profiles.group_id = "'.$city_placement_id.'")';
		}
		//на случаи игнора или отсутствия должности
		$department_post_id = $request->department_post_id;
		if ($department_post_id === 'ignore' ) {
			$AND_profiles_department_post_id = '';
		} else if ($department_post_id === 'NULL') {
			$AND_profiles_department_post_id = ' AND (profiles.department_post_id IS NULL)';
		} else { 
			$AND_profiles_department_post_id = ' AND (profiles.department_post_id = "'.$department_post_id.'")';
		}
		
		return view('academy.assignment_abilities')->
		with(['profiles_assigned_abilities_array' => Profile::selectAssignedAbilities($db,
		$profile_addition_date_start,
		$profile_addition_date_finish,
		$AND_profiles_group_id,
		$AND_profiles_department_post_id), 'abilities' => Ability::all()]);
	}

	public function assignAbilities(Request $request)
	{
		require_once '../t_b/adapt.php';
		
		$razdel='admin';
		require 'lv/t_b/adapt_dostup.php';
		if ($dopusk < 2) {
			my_mesage("У Вас недостаточно прав для доступа к разделу");
		exit;
		}

		if ($request->aus_abilities === null) {
			return view('message_red_redirect')->with(['text' => 'Изменения НЕ внесены!',
			'time' => 3, 'link' => $_SERVER['HTTP_REFERER']]);
		}
	
		$au_ids_ability_ids_setter_logins = [];
		foreach ($request->aus_abilities as $au_id => $abilities) {
			foreach ($abilities as $ability_id) {
				$au_ids_ability_ids_setter_logins[] = [
					'au_id' => $au_id,
					'abilities_id' => $ability_id,
					'setter_login' => $_SESSION['user'],
					'created_at' => Carbon::now()
				];
			}
		}

		$result = DB::table('assigned_abilities')->insert($au_ids_ability_ids_setter_logins);
		if($result == 1) {
			$next_page_link = str_replace('choose_abilities/', 'choose_competencies/', $_SERVER['HTTP_REFERER']);
			return view('message_yellow_redirect')->with(['text' => 'Способности (типы работ) успешно назначены',
			'time' => 3, 'link' => $next_page_link]);

		} else {
			return view('message_red_redirect')->with(['text' => 'Изменения НЕ внесены!',
			'time' => 3, 'link' => $_SERVER['HTTP_REFERER']]);
		}
	}
}
