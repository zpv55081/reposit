<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Tools\Api\Interlocutor;
use Illuminate\Http\Request;

/**
 * Контроллер ключевых показателей
 */
class IndicatorController extends Controller
{
	/**
	 * Заглушка для php 5.4.45
	 * (на php 5.6 в set_error_handler можно просто передавать null)
	 */
	public function emptyErrorHandler()
	{
		// отключенность внутреннего обработчика ошибок PHP
		return true;
	}

	/**
	 * Получить кластеры
	 * @param resource $db дескриптор старинного подключения к БД  
	 * @return array ассоциативный массив кластеров БТУ
	 */
	public function get_clusters($db)
	{
		$sql_clusters = "SELECT
				*
			FROM
				`cluster`
			ORDER BY
				`name`
		";
		$desc_clusters = mysql_query($sql_clusters, $db);
		while ($row = mysql_fetch_assoc($desc_clusters)) {
			$clusters[$row['id']] = $row['name'];
		}
		return $clusters;
	}

	/**
	 * Получить значения "Аварийность сети цель факт" через скрипт простого БТУ
	 * @param resource $db дескриптор старинного подключения к БД  
	 * @return array значения "Аварийность сети цель факт"
	 */
	public function get_network_failure_ta_fa($db)
	{
		// эмулируем данные, генерирующиеся при пользовательском интерфейсе
		$sql_id_group = null;
		$_POST['select_region'] = "0";
		$_POST['show_label'] = "on";
		$_POST['forecast'] = "on";
		$_POST['select_deconside'] = "1";
		$_POST['skip'] = "0";
		$_POST['exept_kk'] = "0";
		$_POST['exept_group'] = "0";
		$_POST['send_report'] = "Отправить";

		$_POST['network_failure_ta_fa_for_api'] = "yes";

		require_once 'inc/alarm/report_target.inc.php';

		// подчищаем суперглобальную переменную
		$_POST = [];

		return $arr_dat;
	}

	/**
	 * Получить значения "Аварийность сети оптическая" через скрипт простого БТУ
	 * @param resource $db дескриптор старинного подключения к БД  
	 * @return array значения "Аварийность сети оптическая"
	 */
	public function get_network_failure_optic($db)
	{
		// эмулируем данные, генерирующиеся при пользовательском интерфейсе
		$sql_id_group = null;
		$_POST['select_region'] = "0";
		$_POST['show_label'] = "on";
		$_POST['forecast'] = "on";
		$_POST['select_deconside'] = "1";
		$_POST['select_exept_kk'] = "0";
		$_POST['select_vid'] = "0";
		$_POST['select_relative'] = "0";
		$_POST['select_level'] = "0";
		$_POST['select_type'] = "0";
		$_POST['select_type_connect'] = "";
		$_POST['send_report'] = "Отправить";

		$_POST['network_failure_optic_for_api'] = "yes";

		require_once 'inc/alarm/report_graph.inc.php';

		// подчищаем суперглобальную переменную
		$_POST = [];

		return $network_failure_optic;
	}

	/**
	 * Получить значения "Доступность сети цель факт" через скрипт простого БТУ
	 * @param resource $db дескриптор старинного подключения к БД  
	 * @return array "Доступность сети цель факт"
	 */
	public function get_network_availability_ta_fa($db)
	{
		// эмулируем данные, генерирующиеся при пользовательском интерфейсе
		$_POST['select_region'] = "0";
		$_POST['show_label'] = "on";
		$_POST['prognose'] = "on";
		$_POST['exept_group'] = "0";
		$_POST['tracking_type'] = "0";
		$_POST['exept'] = "0";
		$_POST['send'] = "Сформировать";

		$_POST['network_availability_ta_fa_for_api'] = "yes";

		require_once 'inc/report/knot_accessibility.inc.php';

		// подчищаем суперглобальную переменную
		$_POST = [];

		return $network_availability_ta_fa;
	}

	/**
	 * Получить значения "Недоступность сети" через скрипт простого БТУ
	 * @param resource $db дескриптор старинного подключения к БД  
	 * @return array "Недоступность сети цель факт"
	 */
	public function get_network_nonavailability($db)
	{
		// эмулируем данные, генерирующиеся при пользовательском интерфейсе
		$_POST['select_region'] = "0";
		$_POST['tracking_type'] = "0";
		$_POST['report_type'] = "0";
		$_POST['send'] = "Сформировать";

		$_POST['network_nonavailability_for_api'] = "yes";

		require_once 'inc/report/knot_accessibility_new.inc.php';

		// подчищаем суперглобальную переменную
		$_POST = [];

		return $network_nonavailability;
	}

	/**
	 * Получить значения "Актуализация сетей - магистральные"
	 * @param resource $db дескриптор старинного подключения к БД  
	 * @return array "Актуализация сетей - магистральные"
	 */
	public function get_target_actualization_ma($db)
	{
		$line_ids_names = $this->get_clusters($db) + [100 => 'Среднее по компании'];

		$sql_target_actualization_ma = "SELECT
				*
			FROM
				`target_actualization_ma`
			WHERE
				`id_type` = '1'
			ORDER BY
				`year`, `month`
		";
		$desc_target_actualization_ma = mysql_query($sql_target_actualization_ma, $db);
		while ($row = mysql_fetch_assoc($desc_target_actualization_ma)) {
			$nonamed_target_actualization_ma[$row['id']] = $row;
		}

		foreach ($nonamed_target_actualization_ma as $point) {
			$target_actualization_ma[$line_ids_names[$point['id_cluster']]]["abscisses"][] =
				json_decode(strtotime("1.{$point['month']}.{$point['year']} 06:00:00") . "000");

			$target_actualization_ma[$line_ids_names[$point['id_cluster']]]["ordinates"][] =
				json_decode($point['value']);
		}

		return $target_actualization_ma;
	}

	/**
	 * Получить значения "Актуализация сетей - домашние"
	 * @param resource $db дескриптор старинного подключения к БД  
	 * @return array "Актуализация сетей - домашние"
	 */
	public function get_target_actualization_ho($db)
	{
		$line_ids_names = $this->get_clusters($db) + [100 => 'Среднее по компании'];

		$sql_target_actualization_ho = "SELECT
				*
			FROM
				`target_actualization_ho`
			WHERE
				`id_type` = '1'
			ORDER BY
				`year`, `month`
		";
		$desc_target_actualization_ho = mysql_query($sql_target_actualization_ho, $db);
		while ($row = mysql_fetch_assoc($desc_target_actualization_ho)) {
			$nonamed_target_actualization_ho[$row['id']] = $row;
		}

		foreach ($nonamed_target_actualization_ho as $point) {
			$target_actualization_ho[$line_ids_names[$point['id_cluster']]]["abscisses"][] =
				json_decode(strtotime("1.{$point['month']}.{$point['year']} 06:00:00") . "000");

			$target_actualization_ho[$line_ids_names[$point['id_cluster']]]["ordinates"][] =
				json_decode($point['value']);
		}

		return $target_actualization_ho;
	}

	/**
	 * Получить значения "Перенос данных - ОК"
	 * @param resource $db дескриптор старинного подключения к БД  
	 * @return array "Перенос данных - ОК"
	 */
	public function get_target_transposition_ok($db)
	{
		$line_ids_names = $this->get_clusters($db) + [100 => 'Среднее по компании'];

		$sql_target_transposition_ok = "SELECT
				*
			FROM
				`target_transposition_ok`
			WHERE
				`id_type` = '1'
			ORDER BY
				`year`, `month`
		";
		$desc_target_transposition_ok = mysql_query($sql_target_transposition_ok, $db);
		while ($row = mysql_fetch_assoc($desc_target_transposition_ok)) {
			$nonamed_target_transposition_ok[$row['id']] = $row;
		}

		foreach ($nonamed_target_transposition_ok as $point) {
			$target_transposition_ok[$line_ids_names[$point['id_cluster']]]["abscisses"][] =
				json_decode(strtotime("1.{$point['month']}.{$point['year']} 06:00:00") . "000");

			$target_transposition_ok[$line_ids_names[$point['id_cluster']]]["ordinates"][] =
				json_decode($point['value']);
		}

		return $target_transposition_ok;
	}

	/**
	 * Получить значения "Перенос данных - ОМ"
	 * @param resource $db дескриптор старинного подключения к БД  
	 * @return array "Перенос данных - ОК"
	 */
	public function get_target_transposition_om($db)
	{
		$line_ids_names = $this->get_clusters($db) + [100 => 'Среднее по компании'];

		$sql_target_transposition_om = "SELECT
				*
			FROM
				`target_transposition_om`
			WHERE
				`id_type` = '1'
			ORDER BY
				`year`, `month`
		";
		$desc_target_transposition_om = mysql_query($sql_target_transposition_om, $db);
		while ($row = mysql_fetch_assoc($desc_target_transposition_om)) {
			$nonamed_target_transposition_om[$row['id']] = $row;
		}

		foreach ($nonamed_target_transposition_om as $point) {
			$target_transposition_om[$line_ids_names[$point['id_cluster']]]["abscisses"][] =
				json_decode(strtotime("1.{$point['month']}.{$point['year']} 06:00:00") . "000");

			$target_transposition_om[$line_ids_names[$point['id_cluster']]]["ordinates"][] =
				json_decode($point['value']);
		}

		return $target_transposition_om;
	}

	/**
	 * Получить значения "Перенос данных - ВОЛС"
	 * @param resource $db дескриптор старинного подключения к БД  
	 * @return array "Перенос данных - ВОЛС"
	 */
	public function get_target_transposition_vols($db)
	{
		$line_ids_names = $this->get_clusters($db) + [100 => 'Среднее по компании'];

		$sql_target_transposition_vols = "SELECT
				*
			FROM
				`target_transposition_vols`
			WHERE
				`id_type` = '1'
			ORDER BY
				`year`, `month`
		";
		$desc_target_transposition_vols = mysql_query($sql_target_transposition_vols, $db);
		while ($row = mysql_fetch_assoc($desc_target_transposition_vols)) {
			$nonamed_target_transposition_vols[$row['id']] = $row;
		}

		foreach ($nonamed_target_transposition_vols as $point) {
			$target_transposition_vols[$line_ids_names[$point['id_cluster']]]["abscisses"][] =
				json_decode(strtotime("1.{$point['month']}.{$point['year']} 06:00:00") . "000");

			$target_transposition_vols[$line_ids_names[$point['id_cluster']]]["ordinates"][] =
				json_decode($point['value']);
		}

		return $target_transposition_vols;
	}

	/**
	 * Получить сведения о показателях через скрипты простого БТУ
	 * @param Request $request
	 * @return array значения ключевых показателей
	 */
	public function getDataFromScripts($request)
	{
		$query_parameters = $this->parse($request);

		// помещаем php в каталог адапторов lv to btu
		chdir('../to_btu/');

		// создаём старинное подключение к БД в переменной $db
		require_once 'db_by_mysql_connect.php';

		// отключаем реагирование Лары на нотайсы и ворнинги легаси-скрипта
		set_error_handler(array($this, "emptyErrorHandler"));

		// устанавливаем php в корневой каталог простого btu
		chdir('../../');

		foreach ($query_parameters['approved_reports'] as $report) {
			$indicators[$report] = call_user_func(array($this, 'get_' . $report), $db);
		}

		return $indicators;
	}

	/**
	 * Разобрать параметры запроса
	 * @param Request $request
	 * @return array
	 */
	private function parse($request)
	{
		$query_parameters = [
			// согласованные отчёты
			'approved_reports' => [],
		];

		// допустимые к выдаче данных отчёты
		$admissibilities = [
			'network_failure_ta_fa',
			'network_failure_optic',
			'network_availability_ta_fa',
			'network_nonavailability',
			'target_actualization_ma',
			'target_actualization_ho',
			'target_transposition_ok',
			'target_transposition_om',
			'target_transposition_vols'
		];

		if ($request->desired_reports) {
			foreach (explode(',', addslashes(htmlentities($request->desired_reports))) as $desired_report) {
				if (in_array($desired_report, $admissibilities))
					$query_parameters['approved_reports'][] = $desired_report;
			}
		}

		return $query_parameters;
	}

	/**
	 * Взять сведения о показателях, исходя из параметров запроса
	 * 
	 * @param Illuminate\Http\Request $request
	 * 
	 * @param App\Tools\Api\Interlocutor $interlocutor объект-собеседник
	 * 
	 * @return Illuminate\Http\Response http-ответ с кодом и, в случае успеха, сведениями о ключевых показателях
	 */
	public function take(Request $request, Interlocutor $interlocutor)
	{
		return $interlocutor->interact($this, 'getDataFromScripts', $request);
	}

	/**
	 * Разместить сведения о показателях по данным запроса
	 * @param Request $request
	 */
	public function place(Request $request)
	{
		// можно запрограммировать алгоритм заполнения базы
	}
}
