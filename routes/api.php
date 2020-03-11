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
  Route::post('/users/check_email','UserController@check_email');
  Route::post('/users/add_user','UserController@add_user');
  Route::post('/users/discard_password','UserController@discard_password');
  
  Route::post('/users/send_reg_data','UserController@send_reg_data');

  Route::get('/roles','UserController@get_all_roles');
  Route::post('/add_dpp_user_role','UserController@add_dpp_user_role');
  Route::post('/delete_dpp_user_role','UserController@delete_dpp_user_role');

  Route::get('/dpp_types','DppController@get_all_dpp_types');
  Route::get('/dpps','DppController@get_all_dpps');
  Route::post('/dpps/delete','DppController@delete_dpp');
  Route::get('/dpps/{dpp}/overview','DppController@get_dpp_overview');
  Route::post('/dpps/stages/start','DppController@start_stage');
  Route::get('/my_dpps','DppController@get_all_my_dpps');

  Route::get('/nsis/nsi_types','IshVersionController@get_nsi_types');
  Route::post('/nsis/add_nsi','IshVersionController@add_nsi');
  Route::post('/nsis/update_nsi','IshVersionController@update_nsi');

  Route::get('/typologies/get_typologies','TypologyController@get_typologies');
  Route::post('/typologies/add_typology','TypologyController@add_typology');
  Route::post('/typologies/add_dtp','TypologyController@add_dtp');
  Route::post('/typologies/remove_dtp','TypologyController@remove_dtp');
  
  Route::get('/nsis/{iv}','IshVersionController@get_nsis');
  Route::get('/nsis/get_nsi/{nsi}','IshVersionController@get_nsi');
  Route::post('/nsis/remove_nsi','IshVersionController@remove_nsi');
    
  Route::post('/zuns/know/delete','ZunVersionController@delete_knowledge');
  Route::post('/zuns/abil/delete','ZunVersionController@delete_ability');
  Route::post('/zuns/skil/delete','ZunVersionController@delete_skill');
  Route::post('/zuns/comp/delete','ZunVersionController@delete_competence');
  Route::post('/questions/delete','OmVersionController@delete_question');

  Route::post('/dpps/{dpp}/{zv}/add_skill','ZunVersionController@add_skill');
  Route::post('/dpps/{dpp}/{zv}/add_ability','ZunVersionController@add_ability');
  Route::post('/dpps/{dpp}/{zv}/add_competence','ZunVersionController@add_competence');
  Route::post('/dpps/{dpp}/{zv}/update_elem','ZunVersionController@update_elem');
  
  Route::get('/dpps/{dpp}/get_zun_version_data/{zv}/unattached','ZunVersionController@get_zun_version_data_unattached');
  Route::get('/dpps/{dpp}/get_zun_version_data/{zv}/attached','ZunVersionController@get_zun_version_data_attached');
  Route::get('/dpps/{dpp}/get_zun_version_data2/{zv}','ZunVersionController@get_zun_version_data2');
  Route::get('/dpps/{dpp}/get_links/{zv}','ZunVersionController@get_links');
  Route::get('/dpps/{dpp}/get_typology','ZunVersionController@get_typology');
  
  Route::get('/dpps/get_skill_info/{sk}','ZunVersionController@get_skill_info');
  Route::get('/dpps/get_ability_info/{ab}','ZunVersionController@get_ability_info');
  Route::get('/dpps/get_knowledge_info/{kn}','ZunVersionController@get_knowledge_info');
  

  Route::get('/dpps/{dpp}/get_ish_version_data/{iv}','IshVersionController@get_ish_version_data');
  Route::post('/dpps/{dpp}/update_ish_version_data/{iv}','IshVersionController@update_ish_version_data');
  Route::get('/dpps/{dpp}/get_stage_data/{stage}','DppStageController@get_stage_data');
  Route::post('/dpps/{dpp}/{stage}/go_next','DppStageController@force_next_stage');

  Route::post('/dpps/{dpp}/add_competence','ZunVersionController@add_competence2');
  Route::post('/dpps/{dpp}/remove_competence','ZunVersionController@remove_competence2');
  
  Route::post('/dpps/{dpp}/add_skill','ZunVersionController@add_skill2');
  Route::post('/dpps/{dpp}/update_skill','ZunVersionController@update_skill2');
  Route::post('/dpps/{dpp}/remove_skill','ZunVersionController@remove_skill2');
  
  Route::post('/dpps/{dpp}/add_ability','ZunVersionController@add_ability2');
  Route::post('/dpps/{dpp}/remove_ability','ZunVersionController@remove_ability2');
  Route::post('/dpps/{dpp}/update_ability','ZunVersionController@update_ability2');

  Route::post('/dpps/{dpp}/add_knowledge','ZunVersionController@add_knowledge2');
  Route::post('/dpps/{dpp}/remove_knowledge','ZunVersionController@remove_knowledge2');
  Route::post('/dpps/{dpp}/update_knowledge','ZunVersionController@update_knowledge2');
  Route::post('/dpps/{dpp}/add_knowledge_link','ZunVersionController@add_knowledge_link2');

  Route::post('/dpps/{dpp}/move_elem','ZunVersionController@move_elem2');
  Route::post('/dpps/{dpp}/disconnect','ZunVersionController@disconnect2');

  Route::get('/dpps/{dpp}/get_knowledges_to_ov/{ov}','DppController@get_knowledges_to_ov');

  Route::get('/dpps/get_prof_levels','IshVersionController@get_prof_levels');

  Route::post('/om_version/{ov}/add_question','OmVersionController@add_question');
  Route::post('/om_version/{ov}/update_question','OmVersionController@update_question');
  Route::get('/dpps/get_question_data/{question}','OmVersionController@get_question_data');

  Route::get('/dpps/{dpp}/config','DppController@get_dpp_to_config');
  Route::get('/stage_types','DppController@get_all_stage_types');
  Route::post('/store_dpp','DppController@store_dpp');
});
Route::group(['middleware' => 'jwt.refresh'], function(){
  Route::get('auth/refresh', 'AuthController@refresh');
});
