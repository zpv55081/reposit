<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//Route::get('/', 'WelcomeController@index');

//Route::get('home', 'HomeController@index');

/*Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);*/

Route::get('/academy/choose_abilities/profiles_filters_display', 'ChooseAbilitiesController@profilesFiltersDisplay');

Route::get('/academy/choose_abilities/profiles_filters_submit', 'ChooseAbilitiesController@profilesFiltersSubmit');

Route::get('/academy/assign_abilities', 'ChooseAbilitiesController@assignAbilities');

Route::get('/academy/choose_competencies/profiles_filters_display', 'ChooseCompetenciesController@profilesFiltersDisplay');

Route::get('/academy/choose_competencies/profiles_filters_submit', 'ChooseCompetenciesController@profilesFiltersSubmit');

Route::get('/academy/assign_competencies', 'ChooseCompetenciesController@assignCompetencies');
