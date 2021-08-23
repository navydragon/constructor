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
  Route::post('/users/update_user','UserController@update_user');

  Route::post('/users/send_reg_data','UserController@send_reg_data');

  Route::get('/roles','UserController@get_all_roles');
  Route::post('/add_dpp_user_role','UserController@add_dpp_user_role');
  Route::post('/delete_dpp_user_role','UserController@delete_dpp_user_role');

  Route::get('/dpp_types','DppController@get_all_dpp_types');
  Route::get('/dpps','DppController@get_all_dpps');
  Route::post('/dpps/delete','DppController@delete_dpp');
  Route::post('/dpps/archive_dpp','DppController@archive_dpp');
  Route::post('/dpps/unarchive_dpp','DppController@unarchive_dpp');
  Route::get('/dpps/{dpp}/overview','DppController@get_dpp_overview');
  Route::post('/dpps/stages/start','DppController@start_stage');
  Route::get('/my_dpps','DppController@get_all_my_dpps');

  Route::get('/dpps/{dpp}/get_knowledges_to_ov/{ov}','DppController@get_knowledges_to_ov');
  Route::get('/dpps/{dpp}/config','DppController@get_dpp_to_config');
  Route::get('/stage_types','DppController@get_all_stage_types');
  Route::post('/store_dpp','DppController@store_dpp');
  Route::get('/dpps/get_prof_levels','IshVersionController@get_prof_levels');
 
  
  Route::get('/mtos/mto_types','MtoController@mto_types');
  Route::post('/mtos/add_mto','MtoController@add_mto');
  Route::post('/mtos/update_mto','MtoController@update_mto');
  Route::get('/mtos/get_mtos/{dpp}','MtoController@get_mtos');
  Route::get('/mtos/get_mto/{mto}','MtoController@get_mto');
  Route::post('/mtos/remove_mto','MtoController@remove_mto');

  Route::post('/mtos/add_base_mtos/{dpp}','MtoController@add_base_mtos');

  Route::get('/typologies/get_typologies','TypologyController@get_typologies');
  Route::post('/typologies/add_typology','TypologyController@add_typology');
  Route::post('/typologies/update_typology','TypologyController@update_typology');
  Route::post('/typologies/remove_part','TypologyController@remove_part');
 

    
  Route::post('/zuns/know/delete','ZunVersionController@delete_knowledge');
  Route::post('/zuns/abil/delete','ZunVersionController@delete_ability');
  Route::post('/zuns/skil/delete','ZunVersionController@delete_skill');
  Route::post('/zuns/comp/delete','ZunVersionController@delete_competence');
  

  Route::post('/dpps/{dpp}/{zv}/add_skill','ZunVersionController@add_skill');
  Route::post('/dpps/{dpp}/{zv}/add_ability','ZunVersionController@add_ability');
  Route::post('/dpps/{dpp}/{zv}/add_competence','ZunVersionController@add_competence');
  Route::post('/dpps/{dpp}/{zv}/update_elem','ZunVersionController@update_elem');
  
  
  Route::get('/dpps/{dpp}/get_zun_version_data2/{zv}','ZunVersionController@get_zun_version_data2');
  Route::get('/dpps/{dpp}/get_zuns_to_om/{zv}','ZunVersionController@get_zuns_to_om');
  
  

  
  

  
  
  
  /* УПРАВЛЕНИЕ ЭТАПАМИ */
  Route::get('/dpps/{dpp}/get_stage_data/{stage}','DppStageController@get_stage_data');
  Route::post('/dpps/{dpp}/{stage}/go_next','DppStageController@force_next_stage');
  /* ------ */

  

  /* 1 ЭТАП */ 
    
    /* ПОЛУЧЕНИЕ ДАННЫХ */
      Route::get('/dpps/{dpp}/get_ish_version_data/{iv}','IshVersionController@get_ish_version_data');
    
    /* СОХРАНЕНИЕ ДАННЫХ */
      Route::post('/ish_version_data/{iv}/requirements','IshVersionController@update_requirements'); //требования
      Route::post('/ish_version_data/{iv}/results','IshVersionController@update_results'); //результаты
  
    /* НСИ */
      Route::post('/ish_version_data/{iv}/nsis/','NsiController@store');
      Route::get('/ish_version_data/{iv}/nsis','NsiController@index');
      Route::post('/ish_version_data/{iv}/nsis/{nsi}/destroy','NsiController@destroy');

    /* ПРИСОЕДЕНИЕ/ОТСОЕДЕНИЕ НОРМАТИВНЫХ ОСНОВАНИЙ К ДПП */
      Route::post('/ish_version_data/{iv}/select_profstandarts','IshVersionController@select_profstandarts');
      Route::post('/ish_version_data/{iv}/select_world_skills','IshVersionController@select_world_skills');
      Route::post('/ish_version_data/{iv}/select_ektses','IshVersionController@select_ektses');
      Route::post('/ish_version_data/{iv}/select_ekses','IshVersionController@select_ekses');
      Route::post('/ish_version_data/{iv}/select_fgoses','IshVersionController@select_fgoses');
      Route::post('/ish_version_data/{iv}/select_corporate_requirements','IshVersionController@select_corporate_requirements');
      Route::post('/ish_version_data/{iv}/unselect_qual_based','IshVersionController@unselect_qual_based');

    /* ДТП */
      Route::post('/ish_version_data/{iv}/dpp_typology_parts','DppTypologyPartController@store'); // добавить типовой раздел
      Route::post('/ish_version_data/{iv}/dpp_typology_parts/{dtp}/destroy','DppTypologyPartController@destroy');
      Route::post('/ish_version_data/{iv}/dpp_typology_parts/{dtp}/update','DppTypologyPartController@update');
      Route::post('/ish_version_data/{iv}/dpp_typology_parts/choose','DppTypologyPartController@choose');
      Route::post('/ish_version_data/{iv}/dpp_typology_parts/reorder','DppTypologyPartController@reorder');

  /* КОНЕЦ 1-ого ЭТАПА */


  /* 2 ЭТАП */
    /* ПОЛУЧЕНИЕ ДАННЫХ */
      Route::get('/dpps/{dpp}/get_zun_version_data/{zv}','ZunVersionController@show');
      
    /* КОМПЕТЕНЦИИ */
      Route::get('/zuns/{zv}/competences/{co}','CompetenceController@show');
      Route::post('/zuns/{zv}/competences/','CompetenceController@store');
      Route::post('/zuns/{zv}/competences/destroy','CompetenceController@destroy');

    /* НАВЫКИ */
      Route::get('/zuns/{zv}/skills/{sk}','SkillController@show');
      Route::post('/zuns/{zv}/skills/','SkillController@store');
      Route::post('/zuns/{zv}/skills/destroy','SkillController@destroy');
    /* УМЕНИЯ */
      Route::get('/zuns/{zv}/abilities/{ab}','AbilityController@show');
      Route::post('/zuns/{zv}/abilities/','AbilityController@store');
      Route::post('/zuns/{zv}/abilities/destroy','AbilityController@destroy');
    /* ЗНАНИЯ */
      Route::get('/zuns/{zv}/knowledges/{kn}','KnowledgeController@show');
      Route::post('/zuns/{zv}/knowledges/','KnowledgeController@store');
      Route::post('/zuns/{zv}/knowledges/destroy','KnowledgeController@destroy');
      Route::post('/zuns/{zv}/knowledges/add_new_link','KnowledgeController@add_new_link');
      Route::post('/zuns/{zv}/knowledges/remove_new_link','KnowledgeController@remove_new_link');
    /* ОБЩЕЕ */
      Route::post('/zuns/{zv}/disconnect','ZunVersionController@disconnect_node');
      
      Route::post('/dpps/{dpp}/move_elem','ZunVersionController@move_elem2');
      Route::post('/dpps/{zv}/get_children','ZunVersionController@get_children');
      Route::post('/dpps/{dpp}/update_order','ZunVersionController@update_order');  
    /* ----- */
    

    
    
    Route::post('/dpps/{dpp}/update_competence','ZunVersionController@update_competence2');
    Route::post('/dpps/{dpp}/update_skill','ZunVersionController@update_skill2');
    Route::post('/dpps/{dpp}/update_ability','ZunVersionController@update_ability2');
    Route::post('/dpps/{dpp}/update_knowledge','ZunVersionController@update_knowledge2');
    
    
    
    
  /* ------- */

  /* 3 ЭТАП */
    Route::get('/dpps/{dpp}/get_om_version_data/{ov}','OmVersionController@show');

    Route::post('/om/{ov}/questions','OmVersionController@question_store');
    Route::post('/om/{ov}/questions/{q}/destroy','OmVersionController@question_destroy');
    Route::post('/om/{ov}/questions/{q}/update','OmVersionController@question_update');

    Route::get('/dpps/get_question_data/{question}','OmVersionController@get_question_data');

    
    Route::post('/om_version/{ov}/update_question','OmVersionController@update_question');

    Route::get('/dpps/get_tasks/{ov}','OmVersionController@get_tasks');
    Route::post('/dpps/add_task','OmVersionController@add_task');
    Route::post('/dpps/tasks/remove_task','OmVersionController@remove_task');
    Route::post('/dpps/tasks/update_specification','OmVersionController@update_specification');
    Route::post('/dpps/tasks/add_subject','OmVersionController@add_subject');
    Route::post('/dpps/tasks/add_object','OmVersionController@add_object');
    Route::post('/dpps/tasks/get_task_object','OmVersionController@get_task_object');
    Route::post('/dpps/tasks/update_object','OmVersionController@update_object');
    Route::post('/dpps/tasks/remove_subject','OmVersionController@remove_subject');
    Route::post('/dpps/tasks/remove_object','OmVersionController@remove_object');
    Route::post('/dpps/tasks/add_task_question','OmVersionController@add_task_question');
    Route::post('/dpps/tasks/get_task_question','OmVersionController@get_task_question');
    Route::post('/dpps/tasks/update_task_question','OmVersionController@update_task_question');
    Route::post('/dpps/tasks/remove_task_question','OmVersionController@remove_task_question');
    Route::get('/dpps/get_task_data/{task}','OmVersionController@get_task_data');
    Route::post('/dpps/get_task_subject_types','OmVersionController@get_task_subject_types');
  /* КОНЕЦ 3 ЭТАПА */

  /* 4 ЭТАП */
    Route::get('/dpps/{dpp}/structure/{sv}/get_sections','StructureSectionController@get_sections');
    Route::get('/dpps/{dpp}/structure/{sv}/get_zuns','StructureSectionController@get_zuns');
    Route::post('/dpps/{dpp}/structure/{sv}/add_theme','StructureSectionController@add_theme');
    Route::post('/dpps/{dpp}/structure/{sv}/update_theme','StructureSectionController@update_theme');
    Route::post('/dpps/{dpp}/structure/{sv}/update_section','StructureSectionController@update_section');
    Route::post('/dpps/{dpp}/structure/{sv}/delete_theme','StructureSectionController@delete_theme');
    Route::post('/dpps/{dpp}/structure/{sv}/delete_section','StructureSectionController@delete_section');
    Route::post('/dpps/{dpp}/structure/{sv}/add_section','StructureSectionController@add_section');
    Route::post('/dpps/{dpp}/structure/{sv}/move_up','StructureSectionController@move_up');
    Route::post('/dpps/{dpp}/structure/{sv}/move_down','StructureSectionController@move_down');
    Route::post('/dpps/{dpp}/structure/{sv}/rebuild','StructureVersionController@rebuild');
  /* КОНЕЦ 4 ЭТАПА */

  /* 5 ЭТАП */
    Route::post('/dpps/{dpp}/content/{cv}/add_lection','LectionController@add_lection');
    Route::get('/dpps/{dpp}/content/get_lection/{lection}','LectionController@get_lection');
    Route::post('/dpps/{dpp}/content/{lection}/add_lection_part','LectionController@add_lection_part');
    Route::post('/dpps/{dpp}/content/{lection}/update_part','LectionController@update_part');
    Route::post('/dpps/{dpp}/content/parts/{part}/move_up','LectionController@move_up_part');
    Route::post('/dpps/{dpp}/content/parts/{part}/move_down','LectionController@move_down_part');
    Route::post('/dpps/{dpp}/content/parts/{part}/remove','LectionController@remove_part');
  /* КОНЕЦ 5 ЭТАПА */
  
  

  /* ПРОФСТАНДАРТЫ */  
    Route::get('/profstandarts','ProfStandartController@index');
    Route::get('/profstandarts/{ps}','ProfStandartController@show');
    Route::post('/profstandarts','ProfStandartController@store');
    Route::patch('/profstandarts/{ps}','ProfStandartController@update');
    Route::delete('/profstandarts/{ps}','ProfStandartController@destroy');
  /* КОНЕЦ ПС */

  /*ТИПЫ НСИ */
    Route::get('/nsi_types','NsiTypeController@index');
  /* ----- */

  /* ЕКТС */
    Route::get('/ekts','EktsController@index');
    Route::get('/ekts/{ekts}','EktsController@show');
    Route::post('/ekts','EktsController@store');
    Route::patch('/ekts/{ekts}','EktsController@update');
    Route::delete('/ekts/{ekts}','EktsController@destroy');
  /* КОНЕЦ ЕКТС */

  /* ЕКС */
    Route::get('/eks','EksController@index');
    Route::get('/eks/{eks}','EksController@show');
    Route::post('/eks','EksController@store');
    Route::patch('/eks/{eks}','EksController@update');
    Route::delete('/eks/{eks}','EksController@destroy');
  /* КОНЕЦ ЕКС */

  /* WORLD SKILLS */
    Route::get('/ws','WorldSkillsController@index');
    Route::get('/ws/{ws}','WorldSkillsController@show');
    Route::post('/ws','WorldSkillsController@store');
    Route::patch('/ws/{ws}','WorldSkillsController@update');
    Route::delete('/ws/{ws}','WorldSkillsController@destroy');
  /* КОНЕЦ WORLD SKILLS */

  /* ФГОСы */
    Route::get('/fgoses','FgosController@index');
    Route::get('/fgoses/{fgos}','FgosController@show');
    Route::post('/fgoses','FgosController@store');
    Route::patch('/fgoses/{fgos}','FgosController@update');
    Route::delete('/fgoses/{fgos}','FgosController@destroy');
  /* КОНЕЦ ФГОСы */

      /* КОРПОРАТИВНЫЕ ТРЕБОВАНИЯ */
      Route::get('/crs','CorporateRequirementController@index');
      Route::get('/crs/{cr}','CorporateRequirementController@show');
      Route::post('/crs','CorporateRequirementController@store');
      Route::post('/crs/{cr}/update','CorporateRequirementController@update');
      Route::post('/crs/{cr}/destroy','CorporateRequirementController@destroy');
    /* КОНЕЦ КТ */

  ///////////////////////////////////////////////////////////////////////////////////////////////
  /* ЗУНЫ СТАРОЕ */
  Route::get('/dpps/{dpp}/get_links/{zv}','ZunVersionController@get_links');
  Route::get('/dpps/{dpp}/get_typology','ZunVersionController@get_typology');
  
  Route::post('/dpps/{dpp}/add_competence','ZunVersionController@add_competence2');
  Route::post('/dpps/{dpp}/remove_competence','ZunVersionController@remove_competence2');
  Route::post('/dpps/{dpp}/add_skill_new/{zv}','ZunVersionController@add_skill_new');
  Route::post('/dpps/{dpp}/remove_skill_new/{zv}','ZunVersionController@remove_skill_new');
  Route::post('/dpps/{dpp}/add_skill','ZunVersionController@add_skill2');
  Route::post('/dpps/{dpp}/remove_skill','ZunVersionController@remove_skill2');
  Route::post('/dpps/{dpp}/add_ability','ZunVersionController@add_ability2');
  Route::post('/dpps/{dpp}/add_ability_new/{zv}','ZunVersionController@add_ability_new');
  Route::post('/dpps/{dpp}/remove_ability','ZunVersionController@remove_ability2');
  Route::post('/dpps/{dpp}/remove_ability_new/{zv}','ZunVersionController@remove_ability_new');
  
  Route::post('/dpps/{dpp}/remove_knowledge','ZunVersionController@remove_knowledge2');
  Route::post('/dpps/{dpp}/add_knowledge','ZunVersionController@add_knowledge2');
  Route::post('/dpps/{dpp}/add_knowledge_new/{zv}','ZunVersionController@add_knowledge_new');
  Route::post('/dpps/{dpp}/remove_knowledge_new/{zv}','ZunVersionController@remove_knowledge_new');
  Route::post('/dpps/{dpp}/disconnect','ZunVersionController@disconnect2');
  Route::post('/dpps/{dpp}/add_knowledge_link','ZunVersionController@add_knowledge_link2');
  Route::post('/dpps/{dpp}/remove_knowledge_link','ZunVersionController@remove_knowledge_link2');
  /* КОНЕЦ ЗУНОВ */
  /* ДОЛЖНОСТНЫЕ КВАЛИФИКАЦИИ - СТАРОЕ */
  Route::post('/dolgkvals/add_dolgkval','DolgKvalController@add_dolgkval');
  Route::post('/dolgkvals/get_dolgkvals','DolgKvalController@get_dolgkvals');
  Route::post('/dolgkvals/get_dolgkval','DolgKvalController@get_dolgkval');
  Route::post('/dolgkvals/remove_dolgkval','DolgKvalController@remove_dolgkval');
  Route::post('/dolgkvals/update_dolgkval','DolgKvalController@update_dolgkval');
  /* КОНЕЦ ДК */
  
  /* ФГОСы - СТАРОЕ */
  Route::post('/fgoses/add_fgos','FgosController@add_fgos');
  Route::post('/fgoses/get_fgoses','FgosController@get_fgoses');
  Route::post('/fgoses/get_fgos_levels','FgosController@get_fgos_levels');
  Route::post('/fgoses/get_fgos','FgosController@get_fgos');
  Route::post('/fgoses/remove_fgos','FgosController@remove_fgos');
  Route::post('/fgoses/update_fgos','FgosController@update_fgos');
  /* КОНЕЦ ФГОС */
  /* ПРОФСТАНДАРТЫ OLD */  
  Route::get('/profstandarts/get_profstandarts','ProfStandartController@get_profstandarts');
  Route::get('/profstandarts/get_profstandart','ProfStandartController@get_profstandart');
  Route::post('/profstandarts/add_profstandart','ProfStandartController@add_profstandart');
  Route::post('/profstandarts/update_profstandart','ProfStandartController@update_profstandart');
  Route::post('/profstandarts/remove_profstandart','ProfStandartController@remove_profstandart');
  /* КОНЕЦ ПС */
  /* СТАРЫЕ НСИ */
  Route::get('/nsis/nsi_types','IshVersionController@get_nsi_types');
  Route::post('/nsis/add_nsi','IshVersionController@add_nsi');
  Route::post('/nsis/update_nsi','IshVersionController@update_nsi');
  Route::get('/nsis/{iv}','IshVersionController@get_nsis');
  Route::get('/nsis/get_nsi/{nsi}','IshVersionController@get_nsi');
  Route::post('/nsis/remove_nsi','IshVersionController@remove_nsi');
  /* ------- */
  
  /* СТАРОЕ 1 этап */
  Route::post('/dpps/{dpp}/ish_version_data/{iv}/select_profstandarts','IshVersionController@select_profstandarts');
  Route::post('/dpps/{dpp}/ish_version_data/{iv}/select_dolgkvals','IshVersionController@select_dolgkvals');
  Route::post('/dpps/{dpp}/ish_version_data/{iv}/select_fgoses','IshVersionController@select_fgoses');
  Route::post('/dpps/{dpp}/update_ish_version_data/{iv}','IshVersionController@update_ish_version_data');
  Route::post('/dpp_typology_parts/move_up','TypologyController@dtp_move_up');
  Route::post('/dpp_typology_parts/move_down','TypologyController@dtp_move_down');
  Route::post('/dpp_typology_parts/remove','TypologyController@dtp_remove');
  Route::post('/dpp_typology_parts/add_dtp','TypologyController@add_dtp');
  Route::post('/dpp_typology_parts/update_dtp','TypologyController@update_dtp');
  Route::post('/dpp_typology_parts/choose_typology','TypologyController@choose_typology');
  Route::post('/typologies/remove_dtp','TypologyController@remove_dtp');

  /* ------ */
  /* СТАРОЕ 2 этап */
  Route::get('/dpps/get_skill_info/{sk}','ZunVersionController@get_skill_info');
  Route::get('/dpps/get_ability_info/{ab}','ZunVersionController@get_ability_info');
  Route::get('/dpps/get_knowledge_info/{kn}','ZunVersionController@get_knowledge_info');
  Route::get('/dpps/get_competence_info/{co}','ZunVersionController@get_competence_info');
  //Route::get('/dpps/{dpp}/get_zun_version_data/{zv}/unattached','ZunVersionController@get_zun_version_data_unattached');
  //Route::get('/dpps/{dpp}/get_zun_version_data/{zv}/attached','ZunVersionController@get_zun_version_data_attached');
  /* ------ */

  /* СТАРОЕ 3 этап */
  Route::post('/om_version/{ov}/add_question','OmVersionController@add_question');
  Route::post('/questions/delete','OmVersionController@delete_question');

  
});
Route::group(['middleware' => 'jwt.refresh'], function(){
  Route::get('auth/refresh', 'AuthController@refresh');
});
