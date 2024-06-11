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
Route::get('/promo','IshVersionController@promo');

Route::get('/create_parts','IshVersionController@create_parts');
Route::get('/set_zun_valid','ZunVersionController@set_zun_valid');
Route::get('/make_positions','ZunVersionController@make_positions');
Route::get('/make_tp_positions','TypologyController@make_tp_positions');
Route::get('/dpps/{dpp}/add_base_mtos', 'MtoController@add_base_mtos');
Route::get('/dpps/{dpp}/export_zun/{zun}/{type}','ExportController@export_zun');
Route::get('/dpps/{dpp}/export_om_questions/{om}','ExportController@export_om_questions'); //экспорт вопросов
Route::get('/dpps/{dpp}/export_tasks','ExportController@export_om_tasks'); // экспорт заданий
Route::get('/dpps/{dpp}/export_plan','ExportController@export_learning_plan'); // экспорт учебного плана


Route::get('/dpps/{dpp}/export_dpp','ExportDppController@export_dpp');
Route::get('/dpps/{dpp}/export_lection','ExportLectionController@export_lection');
Route::get('/dpps/{dpp}/export_practice','ExportPracticeController@export_practice');
Route::get('/dpps/{dpp}/export_lab','ExportLabController@export_lab');

Route::get('/dpps/{dpp}/export_om','ExportOMController@export_om'); // экспорт ОМ


//Route::get('/dpps/{dpp}/export_om','ExportOMController@export_om2');

//Route::get('/dpps/{dpp}/export_dpp_template','ExportController@export_dpp_template');


Route::get('/dpps/{dpp}/export_lection_text','ExportController@export_lection_text');
Route::get('/dpps/{dpp}/export_content','ExportController@export_content');
Route::get('/dpps/{dpp}/export_zun_justification','ExportController@export_zun_justification');
Route::get('/dpps/{dpp}/export_tests_xml','ExportController@export_tests_xml');
Route::get('/dpps/{dpp}/export_part_tests_xml/{position}','ExportController@export_part_tests_xml');
Route::get('/dpps/test_template','ExportController@test_template');

Route::get('/content/{content}/template','ExportController@content_template');
Route::get('/content/{content}/template2','ExportController@content_template2');
Route::get('/content/{content}/download','ExportController@content_download');
Route::get('/content/{content}/additional_files/{af}/download','AdditionalFileController@download');

Route::get('/tasks/{task}/additional_files/{af}/download','TaskAdditionalFileController@download');

Route::get('/{any}', 'ApplicationController')->where('any', '.*');
