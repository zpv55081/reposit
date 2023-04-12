<?php

namespace App\Tools\Api;

use App\Authorize;

/**
 * Участник http-диалога
 */
class Interlocutor
{
	/**
	 * Повзаимодействовать
	 * @param Object $entity звено, имеющее метод обмена данных
	 * @param string $method_name имя метода, производящего обмен данных
	 * @param Illuminate\Http\Request $request параметры http-запроса
	 * @return Illuminate\Http\Response http-ответ с заголовками, кодом и, в случае успеха, контентом
	 */
	public function interact($entity, $method_name, $request)
	{
		// "оживляем" сеанс
		session_start();
		// идентификатор сеанса
		$php_sess_id = session_id();
		// если в "живом" сеансе действительно установлен пользователь
		if (isset($_SESSION['user'])) {
			// на всякий случай сверяем его sessid со значением в бд
			$authenticated = Authorize::where('login', $_SESSION['user'])
				->where('active', 1)
				->where('SesID', $php_sess_id)
				->first();
			// если пользователь аутентифицирован
			if ($authenticated !== null) {
				// обмениваем данные (по распарщенному запросу) и обозначаем перспективный phpsessid
				return response(
					call_user_func(array($entity, $method_name), $request),
					200,
					[
						'Authenticated' => 'sid',
						'ProspectiveKey' => $php_sess_id
					]
				);
			}
			// иначе, если прислан логин и пароль
		} elseif (($request->login != null) && ($request->pass != null)) {
			// проверяем действительность логина и пароля
			$authenticated = Authorize::where('login', addslashes(htmlentities($request->login)))
				->where('active', 1)
				->where('pass', md5(addslashes(htmlentities($request->pass))))
				->first();
			// при правильном лог-пароле
			if ($authenticated !== null) {
				// записываем в сеанс логин текущего пользователя
				$_SESSION['user'] = addslashes(htmlentities($request->login));
				// сохраняем установленный sessionid текущему пользователю в базу данных бту
				Authorize::where('login', addslashes(htmlentities($request->login)))
					->update(['SesID' => $php_sess_id]);
				// обмениваем данные (по распарщенному запросу) и обозначаем перспективный phpsessid
				return response(
					call_user_func(array($entity, $method_name), $request),
					200,
					[
						'Authenticated' => 'lop',
						'ProspectiveKey' => $php_sess_id
					]
				);
				// иначе, сообщаем о неправильном лог-пароле
			} else {
				return response(
					'',
					401, // Unauthorized
					['Mistakes' => 'Wrong login or password']
				);
			}
			// иначе, сообщаем о необходимости присылать валидные аутентификационные реквизиты
		} else {
			return response(
				'',
				401, // Unauthorized
				['Mistakes' => 'Need valid authentication data']
			);
		}
	}
}
