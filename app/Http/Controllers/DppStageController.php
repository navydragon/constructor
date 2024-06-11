<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DppType;
use App\Dpp;
use App\User;
use App\Role;
use App\StageType;
use App\DppStage;
use App\DppUserRole;
use App\ZunVersion;
use Auth;

class DppStageController extends Controller
{
    public function get_stage_data(Dpp $dpp, DppStage $stage)
    {
        $stage->dpp_name = $dpp->name;
        $stage->dpp_total_hours = $dpp->total_hours;
        $stage->type_name = $stage->type->name;
        $stage->zun_version_id = $dpp->zun_version_id;
        $stage->ish_version_id = $dpp->ish_version_id;
        $stage->om_version_id = $dpp->om_version_id;
        $stage->st_version_id = $dpp->st_version_id;
        $stage->ct_version_id = $dpp->ct_version_id;
        return $stage;
    }

    public function check(Dpp $dpp, DppStage $stage)
    {
        $errors = [];
        
        switch($stage->type->id)
        {
            case 1:
                array_push($errors,'Пример ошибки ЗУН');
                $zv = ZunVersion::find($dpp->zun_version_id);
                $non_valid_kns = Knowledge::where('valid',false)->get();
                $non_valid_abs = Ability::where('valid',false)->get();
                $non_valid_sks = Skill::where('valid',false)->get();
                $free_kns = Knowledge::where('ability_id',null)->where('is_through',false)->get();
                $free_abs = Ability::where('skill_id',null)->where('competence_id',null)->get();
                $free_sks = Skill::where('competence_id',null)->get();
                $non_valid_sks = Skill::where('valid',false);
                foreach($non_valid_kns as $kn) {array_push($errors,"Знание ".$kn->name." не заполнено до конца.");}
                foreach($non_valid_abs as $ab) {array_push($errors,"Умение ".$ab->name." не заполнено до конца.");}
                foreach($non_valid_sks as $sk) {array_push($errors,"Навык ".$sk->name." не заполнено до конца.");}
                foreach($free_kns as $kn) {array_push($errors,"Знание ".$kn->name." не принадлежит умению и не является сквозным.");}
                foreach($free_abs as $ab) {array_push($errors,"Умение ".$ab->name." не принадлежит навыку или компетенции.");}
                foreach($free_sks as $sk) {array_push($errors,"Навык ".$sk->name." не принадлежит компетенции.");}
            break;
            case 2:
                array_push($errors,'Пример ошибки ОМ');
            break;
            case 3:
                array_push($errors,'Пример ошибки структуры ДПП');
            break;
            case 4:
                array_push($errors,'Пример ошибки УММ');
            break;
            case 5:
                array_push($errors,'Пример ошибки утверждение');
            break;
            case 6:
                array_push($errors,'Пример ошибки Ввод исходных');
            break;
        }
        return json_encode($errors);
    }


    public function force_next_stage(Dpp $dpp, DppStage $stage)
    {
       $next_stage_type = $stage->type->next_stage;
       $stage->stage_status_id = 6;
       $stage->save();
       if ($next_stage_type != null)
       {
           $new_stage = DppStage::where('dpp_id',$dpp->id)->where('stage_type_id',$next_stage_type)->get()->first();
           $new_stage->stage_status_id = 1;
           $new_stage->save();
           $dpp->current_stage_id = $new_stage->id;
           $dpp->save();
       }
       return $dpp->current_stage_id;
    }
}
