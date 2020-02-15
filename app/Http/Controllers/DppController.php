<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\DppType;
use App\Dpp;
use App\User;
use App\Role;
use App\StageType;
use App\DppStage;
use App\DppUserRole;
use App\ZunVersion;
use App\IshVersion;
use Auth;
class DppController extends Controller
{
    public function get_all_dpp_types()
    {
        $dpp_types = DppType::all();
        foreach ($dpp_types as $dpp_type)
        {
            $dpp_type->text = $dpp_type->name;
            $dpp_type->value = $dpp_type->id;
        }
        return $dpp_types;
    }

    public function get_all_dpps()
    {
        $dpps = Dpp::all();
        foreach ($dpps as $dpp)
        {
            $dpp->type_name = $dpp->type->name;
            $dpp->participants = $dpp->participants;
        }
        return $dpps;
    }

    public function store_dpp(Request $request)
    {
        $dpp = new Dpp;
        $dpp->name = $request->name;
        $dpp->dpp_type_id = $request->type;
        $dpp->author_id = Auth::user()->id;
        $dpp->save();

        $go = true;
        $st = StageType::where('is_first',true)->get()->first();
        while ($go == true)
        {
            $ds = new DppStage;
            $ds->dpp_id = $dpp->id;
            $ds->stage_type_id = $st->id;
            if ($st->is_first == true){
                $ds->stage_status_id = 1;  
            }else{
                $ds->stage_status_id = 2;
            }
            $ds->save();
            if ($st->is_first == true){
                $dpp->current_stage_id = $ds->id;
                $dpp->save();
            }
            if ($st->next_stage == null)
            {
                $go = false;
            }else{
                $st = StageType::find($st->next_stage);
            }
        }
        /*        
        $stage_types = StageType::all();
        foreach ($stage_types as $st) {
            $ds = new DppStage;
            $ds->dpp_id = $dpp->id;
            $ds->stage_type_id = $st->id;
            if ($st->id == 1){
                $ds->stage_status_id = 1;
                
            }else{
                $ds->stage_status_id = 2;
            }
            $ds->save();
            if ($st->id == 1){
                $dpp->current_stage_id = $ds->id;
                $dpp->save();
            }
        }
        */
        $dpp->type_name = $dpp->type->name;
        $dpp->participants = $dpp->participants;
        return $dpp;
    }

    public function get_dpp_to_config(Dpp $dpp)
    {
        $dpp->type_name = $dpp->type->name;
        $dpp->participants = $dpp->participants;
       
        foreach($dpp->participants as $user)
        {
            $u = User::find($user->user_id);
            $user->fullname = $u->fullname;
            $user->user_id = $u->id;
            $r = Role::find($user->role_id);
            $user->rolename = $r->name;
            $user->role_id = $r->id;
        }
        
        return $dpp;
    }

    public function get_all_my_dpps()
    {
        $user_id = Auth::user()->id;
        $my_dpps = DppUserRole::select('dpp_id')->where('user_id',$user_id)->distinct()->get();
        foreach ($my_dpps as $my_dpp)
        {
            $dpp = Dpp::find($my_dpp->dpp_id);
            $my_dpp->name = $dpp->name;
            $my_dpp->status_id = $dpp->status_id;
            $my_dpp->status_name = $dpp->status->name;
            $my_dpp->participants = $dpp->participants;
            $my_dpp->current_stage_name = $dpp->current_stage->type->name;
            foreach($my_dpp->participants as $user)
            {
                $u = User::find($user->user_id);
                $user->fullname = $u->fullname;
                $user->user_id = $u->id;
                $user->email = $u->email;
                $user->phone = $u->phone;
                $r = Role::find($user->role_id);
                $user->rolename = $r->name;
                $user->role_id = $r->id;
                if ($user->user_id == Auth::user()->id)
                {
                    $user->is_me = true;
                }else{
                    $user->is_me = false;
                }
            }
        }
        return $my_dpps;
    }

    public function get_dpp_overview(Dpp $dpp)
    {
        $dpp->type_name = $dpp->type->name;
        $dpp->participants = $dpp->participants;
        $dpp->stages = $dpp->stages;
        foreach($dpp->stages as $stage)
        {
            $ds = DppStage::find($stage->id);
            $stage->name = $ds->type->name;
            $stage->stage_status_name = $ds->status->name;
        }
        foreach($dpp->participants as $user)
        {
            $u = User::find($user->user_id);
            $user->fullname = $u->fullname;
            $user->user_id = $u->id;
            $r = Role::find($user->role_id);
            $user->rolename = $r->name;
            $user->role_id = $r->id;
        }
        
        return $dpp;
    }

    public function get_all_stage_types()
    {
       $st = StageType::all();
       return $st;
    }

    public function start_stage(Request $request)
    {
        $ds = DppStage::find($request->stage_id);
        $ds->stage_status_id = 3;
        $ds->save();
        $dpp = $ds->dpp;
        $dpp->status_id = 2;
        $dpp->save();

        if ($ds->stage_type_id == 1) {
            if ($dpp->zun_versions->count() == 0)
            {
                $zv = new ZunVersion;
                $zv->dpp_id = $dpp->id;
                $zv->author_id = Auth::user()->id;
                $zv->save();
                $dpp->zun_version_id = $zv->id;
                $dpp->save();
            }
        }

        if ($ds->stage_type_id == 6) {
            if ($dpp->ish_versions->count() == 0)
            {
                $iv = new IshVersion;
                $iv->dpp_id = $dpp->id;
                $iv->author_id = Auth::user()->id;
                $iv->save();
                $dpp->ish_version_id = $iv->id;
                $dpp->save();
            }
        }
    }

    public function delete_dpp(Request $request)
    {
        $dpp = Dpp::find($request->id);
        $dpp->current_stage_id = null;
        $dpp->save();
        foreach($dpp->stages as $stage)
        {
            DppStage::destroy($stage->id);
        }
        Dpp::destroy($request->id);
    }
  
}
