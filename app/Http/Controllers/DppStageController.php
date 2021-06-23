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
        $stage->type_name = $stage->type->name;
        $stage->zun_version_id = $dpp->zun_version_id;
        $stage->ish_version_id = $dpp->ish_version_id;
        $stage->om_version_id = $dpp->om_version_id;
        $stage->st_version_id = $dpp->st_version_id;
        return $stage;
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
