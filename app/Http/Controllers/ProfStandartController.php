<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProfStandart;
class ProfStandartController extends Controller
{

    public function index()
    {
        $ps = ProfStandart::orderBy('nameText')->get();
        return $ps;
    }

    public function show($id)
    {
        $ps = ProfStandart::findOrFail($id);
        return $ps;
    }

    public function store(Request $request)
    {
        $doc = $request->document;
        $ps = new ProfStandart;
        $ps->nameText = $doc["nameText"];
        $ps->nameCode = $doc["nameCode"];
        $ps->orderDate = $doc["orderDate"];
        $ps->orderNumber = $doc["orderNumber"];
        $ps->registrationDate = $doc["registrationDate"];
        $ps->registrationNumber = $doc["registrationNumber"];
        $ps->linkQual = $doc["linkQual"];
        $ps->nameQual = $doc["nameQual"];
        $ps->save();
        return $ps;
    }

    public function update($id, Request $request)
    {
        $doc = $request->document;
        $ps = ProfStandart::findOrFail($id);
        $ps->nameText = $doc["nameText"];
        $ps->nameCode = $doc["nameCode"];
        $ps->orderDate = $doc["orderDate"];
        $ps->orderNumber = $doc["orderNumber"];
        $ps->registrationDate = $doc["registrationDate"];
        $ps->registrationNumber = $doc["registrationNumber"];
        $ps->linkQual = $doc["linkQual"];
        $ps->nameQual = $doc["nameQual"];
        $ps->save();
        return $ps;
    }

    public function destroy($id)
    {
        $ps = ProfStandart::findOrFail($id);
        if (count($ps->dpps) > 0)
        {
            return response()->json(['message'=>'forbidden'],403);
        }
        ProfStandart::destroy($id);
        return $id;
    }

    public function get_profstandarts()
    {
        $ps = ProfStandart::orderBy('nameCode')->get();
        return $ps;
    }


    public function get_profstandart(Request $request)
    {
        $ps = ProfStandart::find($request->id);
        return $ps;
    }

    public function add_profstandart(Request $request)
    {
        $ps = new ProfStandart;
        $ps->code = $request->code;
        $ps->name = $request->name;
        $ps->save();
        return $ps;
    }

    public function update_profstandart(Request $request)
    {
        $ps = ProfStandart::find($request->id);
        $ps->code = $request->code;
        $ps->name = $request->name;
        $ps->save();
        return $ps;
    }


    public function remove_profstandart(Request $request)
    {
        ProfStandart::destroy($request->id);
        return $request->id;
    }
}
