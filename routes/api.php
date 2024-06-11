<?php

use Illuminate\Http\Request;
;
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

// экспорт вопросов
Route::get('/dpps/{dpp}/questions/export','OmVersionController@export_questions');
Route::get('/dpps/{dpp}/sections/{section_position}/questions/export','OmVersionController@export_section_questions');
Route::get('/get_base64/{type}/{id}','OmVersionController@get_base_64');

Route::group(['middleware' => 'jwt.auth'], function(){
  Route::get('auth/user', 'AuthController@user');
  Route::patch('/users/{user}/reset_password','UserController@reset_password');
  Route::patch('/users/{user}/change_password','UserController@change_password');
  Route::post('/users/update_user','UserController@update_by_user'); // обновление данных со сторны пользователя
  Route::apiResource('users', 'UserController');
  Route::apiResource('ideas', 'IdeaController');

  Route::apiResource('typologies', 'TypologyController');

  Route::get('/dpps/get_prof_levels','IshVersionController@get_prof_levels');
  Route::apiResource('dpps', 'DppController');
  //Route::get('/users','UserController@get_all_users');
  //Route::post('/users/check_email','UserController@check_email');
  //Route::post('/users/add_user','UserController@add_user');

  //Route::post('/users/send_reg_data','UserController@send_reg_data');

  Route::get('/roles','UserController@roles_index');
  Route::post('/add_dpp_user_role','UserController@add_dpp_user_role');
  Route::post('/delete_dpp_user_role','UserController@delete_dpp_user_role');

  Route::get('/dpp_types','DppController@get_all_dpp_types');

  Route::post('/dpps/stages/start','DppController@start_stage');
  Route::get('/my_dpps','DppController@get_all_my_dpps');

  Route::get('/dpps/{dpp}/get_knowledges_to_ov/{ov}','DppController@get_knowledges_to_ov');
  Route::get('/dpps/{dpp}/config','DppController@get_dpp_to_config');
  Route::get('/stage_types','DppController@get_all_stage_types');
  Route::post('/store_dpp','DppController@store_dpp');




  Route::get('/typologies/get_typologies','TypologyController@get_typologies');
  Route::post('/typologies/add_typology','TypologyController@add_typology');
  Route::post('/typologies/update_typology','TypologyController@update_typology');
  Route::post('/typologies/remove_part','TypologyController@remove_part');



  Route::get('/dpps/{dpp}/get_zun_version_data2/{zv}','ZunVersionController@get_zun_version_data2');
  Route::get('/dpps/{dpp}/get_zuns_to_om/{zv}','ZunVersionController@get_zuns_to_om');





  /* КОПИРОВАНИЕ ДПП */
  Route::post('/dpps/{dpp}/copy','DppController@copy');
  Route::post('/dpps/{dpp}/destroy','DppController@destroy');

    /* ИМПОРТЫ */
    //Route::post('/ish_version_data/{dpp}/ish_data/import/{old_dpp}','IshVersionController@import_ish');
    Route::post('/om/{dpp}/tasks/import/{old_dpp}','OmVersionController@import_tasks');
    Route::post('/zuns/{dpp}/competences/import/{old_dpp}','ZunVersionController@import_competences');
    Route::post('/dpp/{dpp}/typology_parts/apply_structure/{old_dpp}','ZunVersionController@apply_structure');

    /* ЗАГРУЗКА РАЗДЕЛЕННЫХ ФАЙЛОВ */
    Route::get('/dpp/{dpp}/load_split_lections','LectionController@load_split_lections');
    Route::get('/dpp/{dpp}/load_split_practice','LectionController@load_split_practice');


    /* УПРАВЛЕНИЕ ЭТАПАМИ */
  Route::get('/dpps/{dpp}/overview','DppController@get_dpp_overview'); // ПЕРВЫЙ ЗАПРОС
  Route::get('/dpps/{dpp}/get_stage_data/{stage}','DppStageController@get_stage_data');

  Route::get('/dpps/{dpp}/stages/{stage}/check','DppStageController@check');

  Route::post('/dpps/{dpp}/{stage}/go_next','DppStageController@force_next_stage');
  /* ------ */


  /* 1 ЭТАП */

    /* ПОЛУЧЕНИЕ ДАННЫХ */
      Route::get('/dpps/{dpp}/get_ish_version_data/{iv}','IshVersionController@get_ish_version_data2');
      Route::get('/dpps/{dpp}/iv/{iv}','IshVersionController@get_ish_version_data2');
    /* СОХРАНЕНИЕ ДАННЫХ */
      Route::post('/ish_version_data/{iv}/requirements','IshVersionController@update_requirements'); //требования
      Route::post('/ish_version_data/{iv}/period','IshVersionController@update_period'); //результаты
      Route::post('/ish_version_data/{iv}/description','IshVersionController@update_description'); //описание
      Route::post('/ish_version_data/{iv}/direction','IshVersionController@update_direction'); //описание
      Route::post('ish_version_data/{iv}/update_sphere_field','IshVersionController@update_sphere_field');
      Route::post('/ish_version_data/{iv}/form','IshVersionController@update_form'); //форма
      Route::post('/ish_version_data/{iv}/digital_sphere','IshVersionController@update_digital_sphere'); //цифровая сфера
      Route::post('/ish_version_data/{iv}/total_hours','IshVersionController@update_hours'); //цифровая сфера

    /* НСИ */
      Route::post('/ish_version_data/{iv}/nsis/','NsiController@store');
      Route::get('/ish_version_data/{iv}/nsis','NsiController@index');
      Route::get('/ish_version_data/{iv}/nsis/{nsi}','NsiController@show');
      Route::post('/ish_version_data/{iv}/nsis/{nsi}/update','NsiController@update');
      Route::post('/ish_version_data/{iv}/nsis/{nsi}/destroy','NsiController@destroy');
      //перенос НСИ из одной программы в другую
      Route::patch('/nsis/move_nsi','NsiController@move_nsi');
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

      /* ПП */
      Route::patch('ish_version_data/{iv}/qualification_requirements/reorder', 'QualificationRequirementController@reorder');
      Route::resource('ish_version_data/{iv}/qualification_requirements', 'QualificationRequirementController');

      Route::patch('ish_version_data/{iv}/qualification_professional_objects/reorder', 'ProfessionalObjectController@reorder');
      Route::resource('ish_version_data/{iv}/qualification_professional_objects', 'ProfessionalObjectController')->parameters([
        'qualification_professional_objects' => 'qpo'
      ]);

  /* КОНЕЦ 1-ого ЭТАПА */


  /* 2 ЭТАП */
    /* ПОЛУЧЕНИЕ ДАННЫХ */
      Route::get('/dpps/{dpp}/get_zun_version_data/{zv}','ZunVersionController@show');

    /* КОМПЕТЕНЦИИ */
      Route::get('/zuns/{zv}/competences/{co}','CompetenceController@show');
      Route::post('/zuns/{zv}/competences/','CompetenceController@store');
      Route::post('/zuns/{zv}/competences/destroy','CompetenceController@destroy');
      Route::post('/zuns/{zv}/competences/{co}/update','CompetenceController@update');

    /* НАВЫКИ */
      Route::get('/zuns/{zv}/skills/{sk}','SkillController@show');
      Route::post('/zuns/{zv}/skills/','SkillController@store');
      Route::post('/zuns/{zv}/skills/{sk}/update','SkillController@update');
      Route::post('/zuns/{zv}/skills/destroy','SkillController@destroy');

    /* УМЕНИЯ */
      Route::get('/zuns/{zv}/abilities/{ab}','AbilityController@show');
      Route::post('/zuns/{zv}/abilities/','AbilityController@store');
      Route::post('/zuns/{zv}/abilities/{ab}/update','AbilityController@update');
      Route::post('/zuns/{zv}/abilities/destroy','AbilityController@destroy');
    /* ЗНАНИЯ */
      //импорт знаний
      Route::post('/zuns/{zv}/knowledges/search','KnowledgeController@search');
      Route::post('/zuns/{zv}/knowledges/import','ZunVersionController@import_knowledge');

      Route::get('/zuns/{zv}/knowledges/{kn}','KnowledgeController@show');
      Route::post('/zuns/{zv}/knowledges/','KnowledgeController@store');
      Route::post('/zuns/{zv}/knowledges/{kn}/update','KnowledgeController@update');
      Route::post('/zuns/{zv}/knowledges/destroy','KnowledgeController@destroy');
      Route::post('/zuns/{zv}/knowledges/add_new_link','KnowledgeController@add_new_link');
      Route::post('/zuns/{zv}/knowledges/remove_new_link','KnowledgeController@remove_new_link');

    /* ОБЩЕЕ */
      Route::post('/zuns/{zv}/disconnect','ZunVersionController@disconnect_node');
      Route::post('/zuns/{zv}/nodes/{id}/children_order','ZunVersionController@children_order');
      Route::post('/zuns/{zv}/dtps/{dtp}/reorder','ZunVersionController@dtp_knowledge_reorder');
      Route::post('/dpps/{dpp}/move_elem','ZunVersionController@move_elem2');
      Route::post('/zuns/{zv}/rebuild_knowledges','ZunVersionController@rebuild_knowledges');
      Route::post('/zuns/{zv}/reorder_upper_level','ZunVersionController@reorder_upper_level');

      //misc замена заглавных букв в первом слове
      Route::post('/zuns/change_first_letter','ZunVersionController@change_first_letter');
      //Route::post('/dpps/{zv}/get_children','ZunVersionController@get_children');

    /* ----- */




    Route::post('/dpps/{dpp}/update_competence','ZunVersionController@update_competence2');
    Route::post('/dpps/{dpp}/update_skill','ZunVersionController@update_skill2');
    Route::post('/dpps/{dpp}/update_ability','ZunVersionController@update_ability2');
    Route::post('/dpps/{dpp}/update_knowledge','ZunVersionController@update_knowledge2');




  /* ------- */

  /* 3 ЭТАП */
    Route::get('/dpps/{dpp}/get_om_version_data/{ov}','OmVersionController@show');
    Route::get('/om/{ov}/tasks','OmVersionController@tasks_show');
    Route::get('/om/{ov}/parameters','OmVersionController@get_parameters');
    Route::patch('/om/{ov}/parameters','OmVersionController@set_parameters');


    Route::post('/om/{ov}/questions','OmVersionController@question_store');
    Route::post('/om/{ov}/questions/{q}/destroy','OmVersionController@question_destroy');
    Route::post('/om/{ov}/questions/{q}/update','OmVersionController@question_update');
    Route::post('/om/{ov}/questions/{q}/copy','OmVersionController@question_copy');
    Route::post('/om/{ov}/questions/{q}/change_type','OmVersionController@question_change_type');

    Route::post('/om/{ov}/questions/{q}/add_image','OmVersionController@add_image_to_question');
    Route::post('/om/{ov}/questions/{q}/delete_image','OmVersionController@delete_image_from_question');


    Route::post('om/{ov}/tasks/{task}/nsis/select','TaskController@nsis_select');
    Route::post('om/{ov}/tasks/{task}/nsis/{nsi}/unselect','TaskController@nsis_unselect');

    Route::post('om/{ov}/tasks/{task}/mtos/select','TaskController@mtos_select');
    Route::post('om/{ov}/tasks/{task}/mtos/{mto}/unselect','TaskController@mtos_unselect');

    Route::post('om/{ov}/tasks/{task}/subject','TaskController@subject_store');
    Route::post('om/{ov}/tasks/{task}/subjects/{subject}/destroy','TaskController@subject_destroy');


    Route::post('/om/{ov}/tasks/{task}/additional_files','TaskAdditionalFileController@store');
    Route::post('/om/{ov}/tasks/{task}/additional_files/{af}/destroy','TaskAdditionalFileController@destroy');


    Route::post('om/{ov}/tasks/{task}/subjects/{subject}/object','TaskController@object_store');
    Route::post('om/{ov}/tasks/{task}/subjects/{subject}/object/{object}/destroy','TaskController@object_destroy');
    Route::post('om/{ov}/tasks/{task}/subjects/{subject}/object/{object}/update','TaskController@object_update');



    Route::post('/om/{ov}/tasks','TaskController@store');
    Route::post('om/{ov}/tasks/{task}/update','TaskController@update');
    Route::post('om/{ov}/tasks/{task}/destroy','TaskController@destroy');

    Route::post('/dpps/{dpp}/tasks/{task}/add_base_mto_to_task','TaskController@add_base_mto_to_task');
    Route::post('/dpps/{dpp}/tasks/{task}/add_base_nsis_to_task','TaskController@add_base_nsis_to_task');

    Route::post('om/{ov}/tasks/{task}/steps','TaskController@store_step');
    Route::post('om/{ov}/tasks/{task}/steps/{step}/destroy','TaskController@destroy_step');
    Route::post('om/{ov}/tasks/{task}/steps/{step}/update','TaskController@update_step');
    Route::post('om/{ov}/tasks/{task}/steps/reorder','TaskController@steps_reorder');



  /* КОНЕЦ 3 ЭТАПА */

  /* 4 ЭТАП */
    Route::get('/dpps/{dpp}/get_st_version_data/{sv}','StructureVersionController@show');
    Route::get('/structure/{sv}/zoons','StructureVersionController@zoons_show');
    Route::post('/structure/{sv}/sections/{sect}/update','StructureSectionController@update');

    Route::post('/structure/{sv}/sections/{sect}/reorder','StructureSectionController@reorder');

    Route::post('/structure/{sv}/sections/{sect}/move_up','StructureSectionController@move_up');
    Route::post('/structure/{sv}/sections/{sect}/move_down','StructureSectionController@move_down');
    Route::post('/structure/{sv}/rebuild','StructureVersionController@rebuild');
    Route::post('/structure/{sv}/recount_section_hours','StructureVersionController@recount_section_hours');


  /* КОНЕЦ 4 ЭТАПА */

  /* 5 ЭТАП */
    Route::get('/dpps/{dpp}/get_content_data/{ct}','ContentVersionController@show');

    Route::get('/content/{ct}/{theme}/{type}','LectionController@show');
    Route::post('/content/{ct}/{theme}/{type}/upload','LectionController@upload');
    Route::post('/content/{ct}/{theme}/{type}/unlink','LectionController@unlink');

    Route::post('/content/additional_files/{af}/destroy','AdditionalFileController@destroy');
    Route::post('/content/{ct}/{theme}/{type}/additional_files/store','AdditionalFileController@store');
    Route::patch('/content/lections/{lection}/accept_toggle/{type}','LectionController@accept_toggle');
  /* КОНЕЦ 5 ЭТАПА */

  /* 6 ЭТАП */
  Route::get('/dpps/{dpp}/get_approval_data','ApprovalController@show');
  Route::patch('/dpps/{dpp}/designers/reorder','DesignerController@reorder');
  Route::resource('dpps.designers', 'DesignerController');
  Route::get('/dpps/{dpp}/signatory', 'DppController@get_signatory');
  Route::patch('/dpps/{dpp}/signatory', 'DppController@set_signatory');
  //Route::post('/ish_version_data/{iv}/save_annotation','IshVersionController@save_annotation');
  /* КОНЕЦ 6 ЭТАПА */

  /* ПРОФСТАНДАРТЫ */
    Route::get('/profstandarts','ProfStandartController@index');
    Route::get('/profstandarts/{ps}','ProfStandartController@show');
    Route::post('/profstandarts','ProfStandartController@store');
    Route::post('/profstandarts/{ps}/update','ProfStandartController@update');
    Route::post('/profstandarts/{ps}/destroy','ProfStandartController@destroy');
  /* КОНЕЦ ПС */

  /*ТИПЫ НСИ */
    Route::get('/nsi_types','NsiTypeController@index');
  /* ----- */

  /* ЕКТС */
    Route::get('/ekts','EktsController@index');
    Route::get('/ekts/{ekts}','EktsController@show');
    Route::post('/ekts','EktsController@store');
    Route::post('/ekts/{ekts}/update','EktsController@update');
    Route::post('/ekts/{ekts}/destroy','EktsController@destroy');
  /* КОНЕЦ ЕКТС */

  /* ЕКС */
    Route::get('/eks','EksController@index');
    Route::get('/eks/{eks}','EksController@show');
    Route::post('/eks','EksController@store');
    Route::post('/eks/{eks}/update','EksController@update');
    Route::post('/eks/{eks}/destroy','EksController@destroy');
/* КОНЕЦ ЕКС */

  /* WORLD SKILLS */
    Route::get('/ws','WorldSkillsController@index');
    Route::get('/ws/{ws}','WorldSkillsController@show');
    Route::post('/ws','WorldSkillsController@store');
    Route::post('/ws/{ws}/update','WorldSkillsController@update');
    Route::post('/ws/{ws}/destroy','WorldSkillsController@destroy');
/* КОНЕЦ WORLD SKILLS */

  /* ФГОСы */
    Route::get('/fgoses','FgosController@index');
    Route::get('/fgoses/{fgos}','FgosController@show');
    Route::post('/fgoses','FgosController@store');
    Route::post('/fgoses/{fgos}/update','FgosController@update');
    Route::post('/fgoses/{fgos}/destroy','FgosController@destroy');
/* КОНЕЦ ФГОСы */

  /* КОРПОРАТИВНЫЕ ТРЕБОВАНИЯ */
    Route::get('/crs','CorporateRequirementController@index');
    Route::get('/crs/{cr}','CorporateRequirementController@show');
    Route::post('/crs','CorporateRequirementController@store');
    Route::post('/crs/{cr}/update','CorporateRequirementController@update');
    Route::post('/crs/{cr}/destroy','CorporateRequirementController@destroy');
  /* КОНЕЦ КТ */

  /*  МТО */
  Route::post('/dpps/{dpp}/mtos','MtoController@store');
  Route::post('/dpps/{dpp}/mtos/{mto}/destroy','MtoController@destroy');
  Route::post('/dpps/{dpp}/mtos/{mto}/update','MtoController@update');

  /* ------- */

  Route::resource('announcements', 'AnnouncementController');

  Route::get('/misc/parse_fgoses', 'ParseController@fgoses');
  Route::get('/misc/parse_profstandarts', 'ParseController@profstandarts');

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

  /** СТАРОЕ МТО */
  Route::get('/mtos/mto_types','MtoController@mto_types');
  Route::post('/mtos/add_mto','MtoController@add_mto');
  Route::post('/mtos/update_mto','MtoController@update_mto');
  Route::get('/mtos/get_mtos/{dpp}','MtoController@get_mtos');
  Route::get('/mtos/get_mto/{mto}','MtoController@get_mto');
  Route::post('/mtos/remove_mto','MtoController@remove_mto');
  Route::post('/mtos/add_base_mtos/{dpp}','MtoController@add_base_mtos');
  /*_____________*/
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

  Route::post('/zuns/know/delete','ZunVersionController@delete_knowledge');
  Route::post('/zuns/abil/delete','ZunVersionController@delete_ability');
  Route::post('/zuns/skil/delete','ZunVersionController@delete_skill');
  Route::post('/zuns/comp/delete','ZunVersionController@delete_competence');


  Route::post('/dpps/{dpp}/{zv}/add_skill','ZunVersionController@add_skill');
  Route::post('/dpps/{dpp}/{zv}/add_ability','ZunVersionController@add_ability');
  Route::post('/dpps/{dpp}/{zv}/add_competence','ZunVersionController@add_competence');
  Route::post('/dpps/{dpp}/{zv}/update_elem','ZunVersionController@update_elem');
  //Route::get('/dpps/{dpp}/get_zun_version_data/{zv}/unattached','ZunVersionController@get_zun_version_data_unattached');
  //Route::get('/dpps/{dpp}/get_zun_version_data/{zv}/attached','ZunVersionController@get_zun_version_data_attached');
  /* ------ */

  /* СТАРОЕ 3 этап */
  Route::post('/om_version/{ov}/add_question','OmVersionController@add_question');
  Route::post('/questions/delete','OmVersionController@delete_question');
  Route::get('/dpps/get_question_data/{question}','OmVersionController@get_question_data');

  Route::post('/om_version/{ov}/update_question','OmVersionController@update_question');
  /* СТАРОЕ? */
  Route::get('/dpps/get_tasks/{ov}','OmVersionController@get_tasks');
  Route::post('/dpps/add_task','OmVersionController@add_task');
  Route::post('/dpps/tasks/remove_task','OmVersionController@remove_task');
  Route::post('/dpps/tasks/update_specification','OmVersionController@update_specification');
  Route::post('/dpps/tasks/add_subject','OmVersionController@add_subject');
  Route::post('/dpps/tasks/remove_subject','OmVersionController@remove_subject');

  Route::post('/dpps/tasks/add_object','OmVersionController@add_object');
  Route::post('/dpps/tasks/objects/{object}/update','OmVersionController@update_object');
  Route::post('/dpps/tasks/get_task_object','OmVersionController@get_task_object');
  Route::post('/dpps/tasks/remove_object','OmVersionController@remove_object');

  Route::post('/dpps/tasks/add_task_question','OmVersionController@add_task_question');
  Route::post('/dpps/tasks/get_task_question','OmVersionController@get_task_question');
  Route::post('/dpps/tasks/update_task_question','OmVersionController@update_task_question');
  Route::post('/dpps/tasks/remove_task_question','OmVersionController@remove_task_question');
  Route::get('/dpps/get_task_data/{task}','OmVersionController@get_task_data');
  Route::post('/dpps/get_task_subject_types','OmVersionController@get_task_subject_types');
  /* СТАРОЕ 4 этап */
  Route::get('/dpps/{dpp}/structure/{sv}/get_sections','StructureSectionController@get_sections');
    Route::get('/dpps/{dpp}/structure/{sv}/get_zuns','StructureSectionController@get_zuns');
    Route::post('/dpps/{dpp}/structure/{sv}/add_theme','StructureSectionController@add_theme');
    ;
    Route::post('/dpps/{dpp}/structure/{sv}/update_section','StructureSectionController@update_section');
    Route::post('/dpps/{dpp}/structure/{sv}/delete_theme','StructureSectionController@delete_theme');
    Route::post('/dpps/{dpp}/structure/{sv}/delete_section','StructureSectionController@delete_section');
    Route::post('/dpps/{dpp}/structure/{sv}/add_section','StructureSectionController@add_section');

  //////////////////

  /* СТАРОЕ 5 этап */
    Route::post('/dpps/{dpp}/content/{cv}/add_lection','LectionController@add_lection');
    Route::get('/dpps/{dpp}/content/get_lection/{lection}','LectionController@get_lection');
    Route::post('/dpps/{dpp}/content/{lection}/add_lection_part','LectionController@add_lection_part');
    Route::post('/dpps/{dpp}/content/{lection}/update_part','LectionController@update_part');
    Route::post('/dpps/{dpp}/content/parts/{part}/move_up','LectionController@move_up_part');
    Route::post('/dpps/{dpp}/content/parts/{part}/move_down','LectionController@move_down_part');
    Route::post('/dpps/{dpp}/content/parts/{part}/remove','LectionController@remove_part');
  ///////////////////

});
Route::group(['middleware' => 'jwt.refresh'], function(){
  Route::get('auth/refresh', 'AuthController@refresh');
});
