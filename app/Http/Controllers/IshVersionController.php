<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\IshVersion;
use App\Dpp;
class IshVersionController extends Controller
{
    public function get_ish_version_data(Dpp $dpp,IshVersion $iv)
    {
        if ($iv->req_user_edulevel == null) {$iv->req_user_edulevel="";}
        if ($iv->req_user_kval == null) {$iv->req_user_kval="";}
        if ($iv->target == null) {$iv->target="";}
        return $iv;
    }

    public function update_ish_version_data(Dpp $dpp,IshVersion $iv, Request $request)
    {
        $iv->req_user_edulevel = $request->ish_data["req_user_edulevel"];
        $iv->req_user_kval = $request->ish_data["req_user_kval"];
        $iv->target = $request->ish_data["target"];
        $iv->save();
    }
}
