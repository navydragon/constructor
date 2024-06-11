<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ContentVersion;
use App\StructureVersion;
use App\StructureSection;
use App\Dpp;

class ContentVersionController extends Controller
{
    public function show(Dpp $dpp,$ct)
    {
        if ($dpp->ct_version_id == null) {
           $ct = $dpp->create_ct();
        }else{
            $ct = ContentVersion::findOrFail($ct);
        }
        $sections = StructureSection::with(['knowledges' => function ($query) {}])
         ->with(['themes.contents' => function ($query) {$query->orderBy('position');}])
         ->where('parent_id','=', null)
         ->where('st_version_id','=', $dpp->st_version_id)
         ->orderBy('position')
         ->get();

        // foreach ($sections as $section)
        // {
        //     foreach ($section->themes as $theme)
        //     {
        //         $theme->test = $kek;
        //     }
        // }
        
        return $sections;
    }
}
