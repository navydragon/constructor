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
        $ekts->editionDate = $doc["rank"];
        $ekts->editionDate = $doc["organType"];
        $ekts->editionDate = $doc["documentType"];
        $ekts->editionDate = $doc["editionNumber"];
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
        $ekts->editionDate = $doc["editionDate"];
        $ekts->editionDate = $doc["rank"];
        $ekts->editionDate = $doc["organType"];
        $ekts->editionDate = $doc["documentType"];
        $ekts->editionDate = $doc["editionNumber"];
        $ekts->save();
        return $ekts;
    }


    public function destroy($id)
    {
        Ekts::destroy($id);
        return $id;
    }
}
