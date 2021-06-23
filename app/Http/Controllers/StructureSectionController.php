<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Dpp;
use App\StructureVersion;
use App\StructureSection;
class StructureSectionController extends Controller
{
    public function get_sections(Dpp $dpp, StructureVersion $sv)
    {
        
         $sections = $sv->get_sections->where('parent_id','=', null);
         $sections = StructureSection::with(['knowledges' => function ($query) {}])
         ->with(['themes' => function ($query) {}])
         ->get();
         return $sections;
    }
}
