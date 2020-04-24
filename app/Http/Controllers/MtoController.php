<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MtoType;
use App\Mto;
use App\Dpp;

class MtoController extends Controller
{
    public function mto_types()
    {
        $mt = MtoType::all();
        return $mt;
    }

    public function get_mtos(Dpp $dpp)
    {
        $mtos = Mto::where('dpp_id','=',$dpp->id)->get();
        return $mtos;
    }

    public function get_mto($mto)
    {
        $mto = Mto::find($mto);
        return $mto;
    }

    public function add_mto(Request $request)
    {
        $mto = new Mto;
        $mto->dpp_id = $request->dpp_id;
        $mto->type_id = $request->type_id;
        $mto->name = $request->mto_data["name"];
        $mto->measure = $request->mto_data["measure"];
        $mto->save();
        return $mto;
    }

    public function update_mto(Request $request)
    {
        $mto = Mto::find($request->mto_data["id"]);
        $mto->type_id = $request->type_id;
        $mto->name = $request->mto_data["name"];
        $mto->measure = $request->mto_data["measure"];
        $mto->save();
        return $mto;
    }

    public function remove_mto(Request $request)
    {
        Mto::destroy($request->mto_id);
    }
}
