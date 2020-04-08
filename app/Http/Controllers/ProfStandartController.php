<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProfStandart;
class ProfStandartController extends Controller
{
    public function get_profstandarts()
    {
        $ps = ProfStandart::orderBy('code')->get();
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
