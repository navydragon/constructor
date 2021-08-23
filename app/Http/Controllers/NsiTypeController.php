<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\NsiType;
use App\Nsi;
class NsiTypeController extends Controller
{
    public function index()
    {
        $nsi_types = NsiType::where('active',true)->orderBy('name')->get()->toarray();
        array_push($nsi_types,array_pop($nsi_types));
        return json_encode($nsi_types);
    }
}
