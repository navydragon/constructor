<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DolgKval;

class DolgKvalController extends Controller
{
    public function get_dolgkvals()
    {
        $ps = DolgKval::orderBy('name')->get();
        return $ps;
    }

    public function get_dolgkval(Request $request)
    {
        $ps = DolgKval::find($request->id);
        return $ps;
    }

    public function add_dolgkval(Request $request)
    {
        $ps = new DolgKval;
        $ps->name = $request->name;
        $ps->code = 0;
        $ps->save();
        return $ps;
    }

    public function update_dolgkval(Request $request)
    {
        $ps = DolgKval::find($request->id);
        $ps->name = $request->name;
        $ps->save();
        return $ps;
    }
    public function remove_dolgkval(Request $request)
    {
        DolgKval::destroy($request->id);
        return $request->id;
    }
}
