<?php
		//ini_set('error_reporting', E_ALL);
		ini_set('display_errors', 1);
		//ini_set('display_startup_errors', 1);
		/*set_exception_handler(null);*/
		/*//set_error_handler(null);*/
		//die (getcwd());

		//помещаем php в каталог адапторов lv tb
		chdir('../t_b/');
		require_once 'a_co.php';
		require_once 'a_f.php';
		require_once 'a_t.php';
		//require_once 'a_d.php';
		//устанавливаем php в корневой каталог простого b
		chdir('../../');
