<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MtoType;
use App\Mto;
use App\Dpp;

class MtoController extends Controller
{
    

    public function add_base_mtos(Dpp $dpp)
    {
        $a = $dpp->add_base_mtos();
    }

    public function store(Dpp $dpp, Request $request)
    {
        $data = $request->mto;
        $mto = new Mto;
        $mto->dpp_id = $dpp->id;
        switch ($data["typeId"])
        {
            case "1": $mto->type_id = $data["roomType"]; break;
            case "2": $mto->type_id = $data["furnitureType"]; break;
            case "3": $mto->type_id = $data["equipmentType"]; break;
            case "5": $mto->type_id = $data["softwareType"]; break;
            default:  $mto->type_id = $data["typeId"]; break;
        }
        $mto->name = $data["name"];
        $mto->measure = $data["unit"];
        $mto->quantity = $data["count"];
        $mto->note = $data["note"];
        $mto->save();
        return $mto;
    }

    public function update(Dpp $dpp, $id,Request $request)
    {
        $data = $request->mto;
        $mto = Mto::findOrFail($id);
        switch ($data["typeId"])
        {
            case "1": $mto->type_id = $data["roomType"]; break;
            case "2": $mto->type_id = $data["furnitureType"]; break;
            case "3": $mto->type_id = $data["equipmentType"]; break;
            case "5": $mto->type_id = $data["softwareType"]; break;
            default:  $mto->type_id = $data["typeId"]; break;
        }
        $mto->name = $data["name"];
        $mto->measure = $data["unit"];
        $mto->quantity = $data["count"];
        $mto->note = $data["note"];
        $mto->save();
        $mto = Mto::findOrFail($id);
        return $mto;
    }

    public function destroy(Dpp $dpp, $id,Request $request)
    {
        $mto = Mto::findOrFail($id);
        $mto->tasks()->detach();
        Mto::destroy($id);
        return $id;
    }

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
