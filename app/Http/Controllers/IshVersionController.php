<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\IshVersion;
use App\Dpp;
use App\ProfLevel;
class IshVersionController extends Controller
{
    public function get_ish_version_data(Dpp $dpp,IshVersion $iv)
    {
        $arr = [];
        if ($iv->req_user_edulevel == null) {$iv->req_user_edulevel="";}
        if ($iv->req_user_kval == null) {$iv->req_user_kval="";}
        if ($iv->target == null) {$iv->target="";}
        foreach ($iv->prof_levels as $el)
        {
            $arr[] = $el->id;
        }
        $iv->pl = $arr;
        return $iv;
    }

    public function get_prof_levels()
    {
        $pl = ProfLevel::all();
        foreach ($pl as $el)
        {
            $el->value = $el->id;
            $el->text = $el->name;
        }
        return $pl;
    }

    public function update_ish_version_data(Dpp $dpp,IshVersion $iv, Request $request)
    {
        $iv->req_user_edulevel = $request->ish_data["req_user_edulevel"];
        $iv->req_user_kval = $request->ish_data["req_user_kval"];
        $iv->target = $request->ish_data["target"];
        $iv->save();
        $iv->prof_levels()->sync($request->ish_data["pl"]);
        
    }
}
