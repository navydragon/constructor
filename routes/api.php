<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/

Route::post('auth/register', 'AuthController@register');
Route::post('auth/login', 'AuthController@login');
Route::post('auth/logout', 'AuthController@logout');
Route::group(['middleware' => 'jwt.auth'], function(){
  Route::get('auth/user', 'AuthController@user');
  Route::get('/users','UserController@get_all_users');
  Route::get('/roles','UserController@get_all_roles');
  Route::post('/add_dpp_user_role','UserController@add_dpp_user_role');
  Route::post('/delete_dpp_user_role','UserController@delete_dpp_user_role');

  Route::get('/dpp_types','DppController@get_all_dpp_types');
  Route::get('/dpps','DppController@get_all_dpps');
  Route::post('/dpps/delete','DppController@delete_dpp');
  Route::get('/dpps/{dpp}/overview','DppController@get_dpp_overview');
  Route::post('/dpps/stages/start','DppController@start_stage');
  Route::get('/my_dpps','DppController@get_all_my_dpps');

  Route::post('/dpps/{dpp}/{zv}/add_skill','ZunVersionController@add_skill');
  Route::post('/dpps/{dpp}/{zv}/add_ability','ZunVersionController@add_ability');
  Route::get('/dpps/{dpp}/get_zun_version_data/{zv}','ZunVersionController@get_zun_version_data');
  Route::get('/dpps/{dpp}/get_stage_data/{stage}','DppStageController@get_stage_data');

  Route::get('/dpps/{dpp}/config','DppController@get_dpp_to_config');
  Route::get('/stage_types','DppController@get_all_stage_types');
  Route::post('/store_dpp','DppController@store_dpp');
});
Route::group(['middleware' => 'jwt.refresh'], function(){
  Route::get('auth/refresh', 'AuthController@refresh');
});
