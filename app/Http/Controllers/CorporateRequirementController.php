<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CorporateRequirement;

class CorporateRequirementController extends Controller
{
    public function index()
    {
        $crs = CorporateRequirement::orderBy('name')->get();
        return $crs;
    }

    public function show($id)
    {
        $cr = CorporateRequirement::findOrFail($id);
        return $cr;
    }

    public function store(Request $request)
    {
        $doc = $request->document;
        $cr = new CorporateRequirement;
        $cr->name = $doc["name"];
        $cr->text = $doc["text"];
        $cr->full_name = $doc["fullName"];
        $cr->save();
        return $cr;
    }


    public function update($id, Request $request)
    {
        $doc = $request->document;
        $cr = CorporateRequirement::findOrFail($id);
        $$cr->name = $doc["name"];
        $cr->text = $doc["text"];
        $cr->full_name = $doc["fullName"];
        $cr->save();
        return $cr;
    }


    public function destroy($id)
    {
        CorporateRequirement::destroy($id);
        return $id;
    }
}
