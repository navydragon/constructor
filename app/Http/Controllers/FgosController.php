<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Fgos;
use App\FgosLevel;

class FgosController extends Controller
{

    public function index()
    {
        $fgoses = Fgos::with('fgos_level:name')->orderBy('fgos_level_id')->get();
        return $fgoses;
    }

    public function show($id)
    {
        $fgos = Fgos::findOrFail($id);
        return $fgos;
    }

    public function store(Request $request)
    {
        $doc = $request->document;
        $fgos = new Fgos;
        $fgos->name = $doc["name"];
        $fgos->code = $doc["code"];
        $fgos->save();
        return $fgos;
    }


    public function update($id, Request $request)
    {
        $doc = $request->document;
        $fgos = Fgos::findOrFail($id);
        $fgos->name = $doc["name"];
        $fgos->code = $doc["code"];
        $fgos->save();
        return $fgos;
    }


    public function destroy($id)
    {
        Fgos::destroy($id);
        return $id;
    }


    public function get_fgos_levels()
    {
        $fls = FgosLevel::all();
        return $fls;
    }

    public function get_fgoses()
    {
        $ps = Fgos::orderBy('code')->get();
        foreach ($ps as $fl)
        {
            $fl->level = $fl->fgos_level->name;
        }
        return $ps;
    }

    public function get_fgos(Request $request)
    {
        $ps = Fgos::find($request->id);
        return $ps;
    }

    public function add_fgos(Request $request)
    {
        $ps = new Fgos;
        $ps->code = $request->code;
        $ps->name = $request->name;
        $ps->fgos_level_id = $request->fgos_level_id;
        $ps->save();
        $ps->level = $ps->fgos_level->name;
        return $ps;
    }

    public function update_fgos(Request $request)
    {
        $ps = Fgos::find($request->id);
        $ps->code = $request->code;
        $ps->name = $request->name;
        $ps->fgos_level_id = $request->fgos_level_id;
        $ps->save();
        $ps->level = $ps->fgos_level->name;
        return $ps;
    }
    public function remove_fgos(Request $request)
    {
        Fgos::destroy($request->id);
        return $request->id;
    }
}
