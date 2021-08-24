<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ekts;

class EktsController extends Controller
{
    public function index()
    {
        $ekts = Ekts::orderBy('nameProfession')->get();
        return $ekts;
    }


    public function show($id)
    {
        $ekts = Ekts::findOrFail($id);
        return $ekts;
    }

    public function store(Request $request)
    {
        $doc = $request->document;
        $ekts = new Ekts;
        $ekts->chapterName = $doc["chapterName"];
        $ekts->nameProfession = $doc["nameProfession"];
        $ekts->issueNumber = $doc["issueNumber"];
        $ekts->rank = $doc["rank"];
        $ekts->organType = $doc["organType"];
        $ekts->documentType = $doc["documentType"];
        $ekts->editionDate = $doc["editionDate"];
        $ekts->editionNumber = $doc["editionNumber"];
        $ekts->full_name = $doc["fullName"];
        $ekts->save();
        return $ekts;
    }


    public function update($id, Request $request)
    {
        $doc = $request->document;
        $ekts = Ekts::findOrFail($id);
        $ekts->chapterName = $doc["chapterName"];
        $ekts->nameProfession = $doc["nameProfession"];
        $ekts->issueNumber = $doc["issueNumber"];
        $ekts->rank = $doc["rank"];
        $ekts->organType = $doc["organType"];
        $ekts->documentType = $doc["documentType"];
        $ekts->editionDate = $doc["editionDate"];
        $ekts->editionNumber = $doc["editionNumber"];
        $ekts->full_name = $doc["fullName"];
        $ekts->save();
        return $ekts;
    }


    public function destroy($id)
    {
        $doc = Ekts::findOrFail($id);
        if (count($doc->dpps) > 0)
        {
            return response()->json(['message'=>'forbidden'],403);
        }
        Ekts::destroy($id);
        return $id;
    }
}
