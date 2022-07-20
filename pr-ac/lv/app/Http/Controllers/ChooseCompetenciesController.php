<?php

namespace App\Http\Controllers;

use App\Competence;
use App\DepartmentPost;
use App\Http\Controllers\Controller;
use App\Profile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\DB;

class ChooseCompetenciesController extends Controller
{

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
			'headline' => 'ВЫБОР ОБУЧАЮЩИХ КУРСОВ',
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
			$AND_profiles_group_id = ' AND (profiles.group_id = "' . $city_placement_id . '")';
		}
		//на случаи игнора или отсутствия должности
		$department_post_id = $request->department_post_id;
		if ($department_post_id === 'ignore') {
			$AND_profiles_department_post_id = '';
		} else if ($department_post_id === 'NULL') {
			$AND_profiles_department_post_id = ' AND (profiles.department_post_id IS NULL)';
		} else {
			$AND_profiles_department_post_id = ' AND (profiles.department_post_id = "' . $department_post_id . '")';
		}

		$filtred_profiles_array = Profile::selectFiltered(
			$db,
			$profile_addition_date_start,
			$profile_addition_date_finish,
			$AND_profiles_group_id,
			$AND_profiles_department_post_id
		);

		$profiles_with_distributed_competencies = $this->distributeСompetencies(
			$filtred_profiles_array,
			DB::table('assigned_abilities')->get(),
			DB::table('passing_competencies')->get(),
			DB::table('competencies')
				->leftjoin('abilities as a', 'a.id', '=', 'competencies.ability_id')
				->leftjoin('courses as c', 'c.id', '=', 'competencies.course_id')
				->select('competencies.*', 'c.name as course_name', 'a.name as ability_name')
				->get()
		);

		return view('academy.assignment_competencies')
			->with(['p_w_d_c' => $profiles_with_distributed_competencies]);
	}

	public function assignCompetencies(Request $request)
	{
		require_once '../t_b/adapt.php';
		
		$razdel='admin';
		require 'lv/t_b/adapt_dostup.php';
		if ($dopusk < 2) {
			my_mesage("У Вас недостаточно прав для доступа к разделу");
		exit;
		}

		if ($request->competencies_ids === null) {
			return view('message_red_redirect')->with([
				'text' => 'Изменения НЕ внесены!',
				'time' => 3, 'link' => $_SERVER['HTTP_REFERER']
			]);
		}

		$assigned_сompetencies_ids = [];
		foreach ($request->competencies_ids as $competence_id => $assign) {
			$insertion_result = DB::table('passing_competencies')
				->insert([
					'au_id' => $request->au_id,
					'competencies_id' => $competence_id,
					'begin_date' => Carbon::now(),
					'created_at' => Carbon::now(),
					'setter_login' => $_SESSION['user']
				]);
			if ($insertion_result == 1) {
				$assigned_сompetencies_ids[] = $competence_id;
			}
		}

		$au_fio = $request->au_fio;
		
		if (env('APP_ENV') == 'production') {
			$recipients = [$request->au_email];
		} else {
			$recipients = ['p.zajcev@k-t.org']; //почта разработчика (для тестирований)
		}
		if ($request->additional_email != '') {
			$recipients[] = $request->additional_email;
		}

		//"костыль" отправки emailов "через простую б"
		$assigned_сompetencies_ids_uc = urlencode(implode(', ', $assigned_сompetencies_ids));
		$au_fio_uc = urlencode($au_fio);
		$recipients_uc = urlencode(implode(', ', $recipients));

		$emails_sender_link = (str_replace(
			"lv/academy/choose_competencies/profiles_filters_submit?",
			"academy.php?page=as_co_sending_emails&",
			$_SERVER['HTTP_REFERER'])).
			"&aciuc=$assigned_сompetencies_ids_uc&fiouc=$au_fio_uc&recipientsuc=$recipients_uc";
		
		return view('message_yellow_redirect')->with([
			'text' => '',
			'time' => 0, 'link' => $emails_sender_link
		]); /*после отправки emailов  "простая б" отобразит сообщение и
		средиректит в lv/.../choose_competencies...(по старым фильтрам)*/
	}

	/**
	 * Распределить компетенции для профайлов по категориям
	 * @param array $profiles контрольные профайлы
	 * @param array $assigned_abilities все существующие назначенные способности
	 * @param array $passing_competencies все сущности прохождения компетенций
	 * @param array $all_competencies все существующие компетенции (уже отсортированные "по очередям")
	 * @return array $p_w_d_c profiles with distributed competencies 
	 */
	public function distributeСompetencies(
		$profiles,
		$assigned_abilities,
		$passing_competencies,
		$all_competencies
	) {
		$p_w_d_c = [];
		foreach ($profiles as $profile) {
			$p_w_d_c[$profile['profile_id']] = $profile;
			$p_w_d_c[$profile['profile_id']]['requested_abilities_ids'] = [];
			$p_w_d_c[$profile['profile_id']]['nominated_abilities_ids'] = [];
			$p_w_d_c[$profile['profile_id']]['started_competencies_ids'] = [];
			$p_w_d_c[$profile['profile_id']]['passed_competencies_ids'] = [];
			$p_w_d_c[$profile['profile_id']]['competencies']['passed'] = [];
			$p_w_d_c[$profile['profile_id']]['competencies']['started'] = [];
			$p_w_d_c[$profile['profile_id']]['competencies']['available'] = [];
			$p_w_d_c[$profile['profile_id']]['competencies']['disabled'] = [];
			foreach ($assigned_abilities as $assigned_ability) {
				if (($assigned_ability->au_id == $profile['au_id']) and
					($assigned_ability->is_deleted == NULL)
				) {
					//идентификаторы способностей, назначенные текущему профайлу
					$p_w_d_c[$profile['profile_id']]['nominated_abilities_ids'][] = $assigned_ability->abilities_id;
				}
			}
			foreach ($passing_competencies as $passing_competence) {
				if ((($passing_competence->au_id == $profile['au_id'])) and
					($passing_competence->request_date != NULL) and
					($passing_competence->begin_date == NULL) and
					$passing_competence->is_deleted == NULL
				) {
					//идентификаторы компетенций запрошенных текущим профайлом
					$p_w_d_c[$profile['profile_id']]['requested_abilities_ids'][] = $passing_competence->competencies_id;
				} elseif ((($passing_competence->au_id == $profile['au_id'])) and
					($passing_competence->begin_date != NULL) and
					$passing_competence->is_deleted == NULL
				) {
					if ($passing_competence->completion_date == NULL) {
						//идентификаторы начатых, но непройденных компетенций текущего профайла
						$p_w_d_c[$profile['profile_id']]['started_competencies_ids'][] = $passing_competence->competencies_id;
					} else {
						//идентификаторы пройденных компетенций текущего профайла
						$p_w_d_c[$profile['profile_id']]['passed_competencies_ids'][] = $passing_competence->competencies_id;
					}
				}
			}
			//идентификатор текущей способности
			$current_ability_id = '';
			foreach ($all_competencies as $competence) {
				//массив идентификаторов компетенций предыдущих текущей
				$previous_competencies_ids_array = explode(';', $competence->previous);
				//определяем схождение массивов компетенций (какие пройденные имеются в нужных предыдущих)
				$similarity_previous_in_passed = array_intersect(
					$previous_competencies_ids_array,
					$p_w_d_c[$profile['profile_id']]['passed_competencies_ids']
				);
				//если идентификатор текущей компетенции находится в массиве пройденых компетенций текущего профайла
				if (in_array($competence->id, $p_w_d_c[$profile['profile_id']]['passed_competencies_ids'])) {
					$p_w_d_c[$profile['profile_id']]['competencies']['passed'][] = $competence;
					//если идентиф. текущей компет. находится в массиве начатых, но непройденных компет. текущего профайла
				} elseif ((in_array($competence->id, $p_w_d_c[$profile['profile_id']]['started_competencies_ids']))) {
					$p_w_d_c[$profile['profile_id']]['competencies']['started'][] = $competence;
					//если у текущего профайла (полностью) пройдена какая-либо компетенция предыдущая текущей ИЛИ(в т.ч. и)
				} elseif (
					!empty($similarity_previous_in_passed) or
					//если способность текущей компетенции назначена текущему профайлу И
					(in_array($competence->ability_id, $p_w_d_c[$profile['profile_id']]['nominated_abilities_ids']) and
						//эта компетенция первоочередная в текущей способности
						($current_ability_id != $competence->ability_id))
				) {

					$p_w_d_c[$profile['profile_id']]['competencies']['available'][] = $competence;
				} else {
					$p_w_d_c[$profile['profile_id']]['competencies']['disabled'][] = $competence;
				}

				$current_ability_id = $competence->ability_id;
			}
		}
		return $p_w_d_c;
	}
}
