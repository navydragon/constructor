<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/create_parts','IshVersionController@create_parts');
Route::get('/set_zun_valid','ZunVersionController@set_zun_valid');
Route::get('/make_positions','ZunVersionController@make_positions');
Route::get('/make_tp_positions','TypologyController@make_tp_positions');
Route::get('/dpps/{dpp}/add_base_mtos', 'MtoController@add_base_mtos');
Route::get('/dpps/{dpp}/export_zun/{zun}','ExportController@export_zun');
Route::get('/dpps/{dpp}/export_om_questions/{om}','ExportController@export_om_questions');
Route::get('/dpps/{dpp}/export_ych_plan/{sv}','ExportController@export_learning_plan');
Route::get('/dpps/{dpp}/export_dpp','ExportController@export_dpp');
Route::get('/dpps/{dpp}/export_content','ExportController@export_content');

Route::get('/{any}', 'ApplicationController')->where('any', '.*');