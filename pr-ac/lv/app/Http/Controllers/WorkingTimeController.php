<?php

namespace App\Http\Controllers;

use App\Auth;
use App\Group;
use App\WorkingTime;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WorkingTimeController extends Controller
{
	/**
	 * Отображение расписаний в городах
	 */
	public function index_days()
	{
		require_once '../to_b/adapt.php';
		$razdel = 'admin';

		require 'lv/to_b/adapt_dostup.php';
		if ($dopusk < 1) {
			my_mesage("У Вас недостаточно прав для доступа к разделу");
			exit;
		}

		return view('working_time.show_days')
			->with('groups_dates', $this->getGroupsDates());
	}

	/**
	 * Отображение часов в расписаниях
	 */
	public function index_hours()
	{
		require_once '../to_b/adapt.php';

		$razdel = 'admin';
		require 'lv/to_b/adapt_dostup.php';
		if ($dopusk < 1) {
			my_mesage("У Вас недостаточно прав для доступа к разделу");
			exit;
		}

		return view('working_time.show_hours')
			->with(
				'groups',
				WorkingTime::select('working_time.group_id', 'group.name as group_name')
					->leftJoin('group', 'working_time.group_id', '=', 'group.id_group')
					->whereNotIn('group.id_group', [0, 1, 9, 9905])
					->distinct()
					->orderBy('group_name')
					->get()
			)
			->with('city_name', '')
			->with(
				'w_t',
				WorkingTime::where('group_id', '0')
					->whereBetween(
						'date',
						[
							date('Y', strtotime('-1 years')) . '-01-01',
							date('Y', strtotime('+1 years')) . '-12-31'
						]
					)
					->orderBy('date')
					->get()
			);
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Request $request)
	{
		require_once '../to_b/adapt.php';
		$razdel = 'admin';

		require 'lv/to_b/adapt_dostup.php';
		if ($dopusk < 1) {
			my_mesage("У Вас недостаточно прав для доступа к разделу");
			exit;
		}

		$city = $this->defineCity($request);

		$interval_dates = $this->defineDates($request);

		return view('working_time.show_hours')
			->with(
				'groups',
				WorkingTime::select('working_time.group_id', 'group.name as group_name')
					->leftJoin('group', 'working_time.group_id', '=', 'group.id_group')
					->whereNotIn('group.id_group', [0, 1, 9, 9905])
					->distinct()
					->orderBy('group_name')
					->get()
			)
			->with('city_name', $city[1])
			->with(
				'w_t',
				WorkingTime::where('group_id', $city[0])
					->whereBetween('date', [$interval_dates['start'], $interval_dates['finish']])
					->orderBy('date')
					->get()
			);
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(Request $request)
	{
		require_once '../to_b/adapt.php';

		$current_user_d_p_id = Auth::select('department_post.id')
			->leftJoin('profiles', 'profiles.Auth_id', '=', 'Auth.id_Auth')
			->leftJoin('department_post', 'department_post.id', '=', 'profiles.department_post_id')
			->where('login', $_SESSION['user'])
			->first()->id;

		$razdel = 'admin';
		require 'lv/to_b/adapt_dostup.php';
		// доступно только Терр., Рег. управляющим и Руководителю управления
		if ($dopusk < 1 or !in_array($current_user_d_p_id, [11, 20, 21])) {
			my_mesage("У Вас недостаточно прав для доступа к разделу");
			exit;
		}

		$default_editing_dates = [];
		if ($request->default_dates_kind) {
			$start_date = $this->defineDates($request)['start'];
			$finish_date = $this->defineDates($request)['finish'];
			$default_editing_dates =
				$this->splitDaysByKind($start_date, $finish_date)[$request->default_dates_kind];
		}
		return view('working_time.edit_hours')
			->with('default_editing_dates', $default_editing_dates)
			->with(
				'groups',
				Group::select('id_group as group_id', 'name as group_name')
					->whereNotIn('id_group', [0, 1, 9, 9905])
					->orderBy('group_name')
					->get()
			);
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update()
	{
		require_once '../to_b/adapt.php';

		$current_user_d_p_id = Auth::select('department_post.id')
			->leftJoin('profiles', 'profiles.Auth_id', '=', 'Auth.id_Auth')
			->leftJoin('department_post', 'department_post.id', '=', 'profiles.department_post_id')
			->where('login', $_SESSION['user'])
			->first()->id;

		$razdel = 'admin';
		require 'lv/to_b/adapt_dostup.php';
		// доступно только Терр., Рег. управляющим и Руководителю управления
		if ($dopusk < 1 or !in_array($current_user_d_p_id, [11, 20, 21])) {
			my_mesage("У Вас недостаточно прав для доступа к разделу");
			exit;
		}

		$city = $this->defineCity();

		if (isset($_REQUEST['working_hours'])) {
			$hours_string = implode(';', $_REQUEST['working_hours']);
		} else {
			$hours_string = '';
		}

		if ($_REQUEST['control_date'] == '') {
			return view('message_red_redirect')->with([
				'text' => 'Изменения НЕ внесены!',
				'time' => 3, 'link' => $_SERVER['HTTP_REFERER']
			]);
		}

		$control_dates_array = explode(';', mb_substr($_REQUEST['control_date'], 0, -1));

		$existing_db_dates = WorkingTime::where('group_id', $city[0])
			->where('date', '>=', $control_dates_array[0])
			->where('date', '<=', last($control_dates_array))
			->get();

		$existing_db_dates_array = [];

		foreach ($existing_db_dates as $date) {
			$existing_db_dates_array[] = $date->date;
		}

		foreach ($control_dates_array as $date) {
			if (in_array($date, $existing_db_dates_array)) {
				$updation_result = WorkingTime::where('group_id', $city[0])
					->where('date', $date)
					->update([
						'hours' => $hours_string,
						'setter_login' => $_SESSION['user']
					]);
				if ($updation_result === 1) {
					$dates_hours[$date] = $hours_string;
				}
			} else {
				$insertion_result = WorkingTime::create([
					'group_id' => $city[0],
					'date' => $date,
					'hours' => $hours_string,
					'setter_login' => $_SESSION['user']
				]);
				$dates_hours[$insertion_result->date] = $insertion_result->hours;
			}
		}

		return view('message_yellow_redirect')
			->with([
				'text' => $city[1] . '<br> Для дат установлены рабочие часы: <br>' . var_export($dates_hours, true),
				'time' => 60,
				'link' => env('APP_URL') . '/working_time/edit'
			]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 */
	public function destroy(Request $request)
	{
		
	}


	/**
	 * Удалить индивидуальное расписание
	 */
	protected function delete(Request $request)
	{
		require_once '../to_b/adapt.php';

		$current_user_d_p_id = Auth::select('department_post.id')
			->leftJoin('profiles', 'profiles.Auth_id', '=', 'Auth.id_Auth')
			->leftJoin('department_post', 'department_post.id', '=', 'profiles.department_post_id')
			->where('login', $_SESSION['user'])
			->first()->id;

		$razdel = 'admin';
		require 'lv/to_b/adapt_dostup.php';
		// доступно только Терр., Рег. управляющим и Руководителю управления
		if ($dopusk < 1 or !in_array($current_user_d_p_id, [11, 20, 21])) {
			my_mesage("У Вас недостаточно прав доступа");
			exit;
		}

		$this->destroy($request);

		return view('message_yellow_redirect')
		->with([
			'text' => "Индивидуальное расписание $request->id_group удалено",
			'time' => 5,
			'link' => $_SERVER['HTTP_REFERER']
		]);
	}

	/**
	 *	Определить город из запроса
	 *  @return array ключ0: idшник, ключ1: название
	 */
	public function defineCity()
	{
		if ($_REQUEST['schedule_type'] === "--") {
			echo view('message_red_redirect')
				->with([
					'text' => 'Тип расписания не выбран!',
					'time' => 3,
					'link' => $_SERVER['HTTP_REFERER']
				]);
			exit();
		} elseif ($_REQUEST['schedule_type'] === "simple") {
			$city = [0, ''];
		} else {
			$city = explode(':', $_REQUEST['city']);
		}

		return $city;
	}

	/**
	 * Определить крайние даты из запроса
	 * @return array ключ'start': начальная дата, ключ'finish': конечная дата
	 */
	public function defineDates(Request $request)
	{
		//на случай пустого ввода начальной даты
		if ($request->date_start == "") {
			$interval_dates['start'] = date('Y-m-d', strtotime('-5 years'));
		} else {
			$interval_dates['start'] = $request->date_start;
		}
		//на случай пустого ввода конечной даты
		if ($request->date_finish == "") {
			$interval_dates['finish'] = date('Y-m-d', strtotime('+5 years'));
		} else {
			$interval_dates['finish'] = "$request->date_finish 23:59:59";
		}

		return $interval_dates;
	}

	/**
	 * Распределить дни по виду Пн-Пт/Сб-Вс
	 * @param string $start_date - начальная дата интервала
	 * @param string $finish_date - конечная дата интервала
	 * @return array ключ'monfri': даты Пн-Пт, ключ'satsun': даты Сб-Вс
	 */
	public function splitDaysByKind($start_date, $finish_date)
	{
		$days_by_kind = [
			'monfri' => [],
			'satsun'  => []
		];
		// от стартовой до финишной прибавляем к дате по одному дню для проведения операций
		for (
			$operating_date_unix = strtotime($start_date);
			$operating_date_unix <= strtotime($finish_date);
			$operating_date_unix = strtotime("+1 day", $operating_date_unix)
		) {
			// если оперируемый день это суббота или воскресенье
			if (
				(((date('w', $operating_date_unix)) == 6))
				or
				(((date('w', $operating_date_unix)) == 0))
			) {
				$days_by_kind['satsun'][] = date('Y-m-d', $operating_date_unix);
			} else {
				$days_by_kind['monfri'][] = date('Y-m-d', $operating_date_unix);
			}
		}

		return $days_by_kind;
	}

	/**
	 * Получить даты расписаний городов
	 * @return array
	 */
	public function getGroupsDates()
	{	
		return WorkingTime::select(
			'working_time.id',
			'working_time.group_id',
			'group.name as group_name',
			'working_time.date',
			'working_time.hours'
		)
			->leftJoin('group', 'working_time.group_id', '=', 'group.id_group')
			->whereNotIn('group.id_group', [1, 9, 9905])
			->orderBy('group_name', 'working_time.date')
			->get();
	}
}
