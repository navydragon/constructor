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
        return $stage;
    }
}
