<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\IshVersion;
use App\Dpp;
use App\ProfLevel;
use App\NsiType;
use App\Nsi;
use Auth;
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

    public function get_nsi_types()
    {
        $types = NsiType::all();
        return $types;
    }

    public function get_nsis(IshVersion $iv, Request $request)
    {
        $nsis = $iv->nsis;
        return $nsis;
    }

    public function get_nsi(Nsi $nsi)
    {
        return $nsi;
    }

    public function add_nsi(Request $request)
    {
        $data = $request->nsi_data;
        $nsi = new Nsi;
        $nsi->type_id = $data["type"];
        $nsi->name = $data["name"];
        $nsi->authors = $data["authors"];
        $nsi->output = $data["output"];
        $nsi->url = $data["url"];
        $nsi->ish_version_id = $request->ish_version_id;
        $nsi->author_id = Auth::user()->id;
        $nsi->save();
        return $nsi;
    }

    public function update_nsi(Request $request)
    {
        $data = $request->nsi_data;
        $nsi = Nsi::find($data["id"]);
        $nsi->type_id = $data["type_id"];
        $nsi->name = $data["name"];
        $nsi->authors = $data["authors"];
        $nsi->output = $data["output"];
        $nsi->url = $data["url"];
        $nsi->author_id = Auth::user()->id;
        $nsi->save();
        return $nsi;
    }

    public function remove_nsi(Request $request)
    {
        Nsi::destroy($request->nsi_id);
        return $request->nsi_id;
    }
}
