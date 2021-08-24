<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\WorldSkills;

class WorldSkillsController extends Controller
{
    public function index()
    {
        $ws = WorldSkills::orderBy('name')->get();
        return $ws;
    }

    public function show($id)
    {
        $ws = WorldSkills::findOrFail($id);
        return $ws;
    }

    public function store(Request $request)
    {
        $doc = $request->document;
        $ws = new WorldSkills;
        $ws->name = $doc["name"];
        $ws->code = $doc["code"];
        $ws->save();
        return $ws;
    }

    public function update($id, Request $request)
    {
        $doc = $request->document;
        $ws = WorldSkills::findOrFail($id);
        $ws->name = $doc["name"];
        $ws->code = $doc["code"];
        $ws->save();
        return $ws;
    }

    public function destroy($id)
    {
        $doc = WorldSkills::findOrFail($id);
        if (count($doc->dpps) > 0)
        {
            return response()->json(['message'=>'forbidden'],403);
        }
        WorldSkills::destroy($id);
        return $id;
    }
}
