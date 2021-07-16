<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\StructureVersion;
use App\StructureSection;
use App\Dpp;
use Illuminate\Support\Facades\DB;
class StructureVersionController extends Controller
{
    public function rebuild(Dpp $dpp,StructureVersion $sv)
    {
        $res = $sv->rebuild();
    }
}
