<?php

namespace App\Http\Controllers;
use App\Eks;

use Illuminate\Http\Request;

class EksController extends Controller
{
    public function index()
    {
        $eks = Eks::orderBy('nameProfession')->get();
        return $eks;
    }

    public function show($id)
    {
        $eks = Eks::findOrFail($id);
        return $eks;
    }

    public function store(Request $request)
    {
        $doc = $request->document;
        $eks = new Eks;
        $eks->chapterName = $doc["chapterName"];
        $eks->nameProfession = $doc["nameProfession"];
        $eks->editionDate = $doc["editionDate"];
        $eks->save();
        return $eks;
    }


    public function update($id, Request $request)
    {
        $doc = $request->document;
        $eks = Eks::findOrFail($id);
        $eks->chapterName = $doc["chapterName"];
        $eks->nameProfession = $doc["nameProfession"];
        $eks->editionDate = $doc["editionDate"];
        $eks->save();
        return $eks;
    }


    public function destroy($id)
    {
        $doc = Eks::findOrFail($id);
        if (count($doc->dpps) > 0)
        {
            return response()->json(['message'=>'forbidden'],403);
        }
        Eks::destroy($id);
        return $id;
    }
}
