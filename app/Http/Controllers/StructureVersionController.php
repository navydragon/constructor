<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\StructureVersion;
use App\StructureSection;
use App\Dpp;
class StructureVersionController extends Controller
{
    public function rebuild(Dpp $dpp,StructureVersion $sv)
    {
        $sections = $sv->get_sections;
        foreach ($sections as $section)
        {
            $themes = $section->themes;
            foreach ($themes as $theme)
            {
                $theme->knowledges()->detach();
                $theme->abilities()->detach();
                $theme->skills()->detach();
                StructureSection::destroy($theme->id);
            }
            $section->knowledges()->detach();
            $section->abilities()->detach();
            $section->skills()->detach();
            StructureSection::destroy($section->id);
        }
        $n = 1;
        foreach($dpp->typology_parts as $tp)
        {
            $section = new StructureSection;
            $section->name = $tp->name;
            $section->position = $n;
            $section->st_version_id = $sv->id;
            $section->save();
            $n++;
            $m=1;
            foreach ($tp->get_knowledges as $kn)
            {
                $theme = new StructureSection;
                $fc = mb_strtoupper(mb_substr($kn["what"], 0, 1));
                $theme->name = $fc.mb_substr($kn["what"], 1);
                $theme->position = $m;
                $theme->st_version_id = $sv->id;
                $theme->parent_id = $section->id;
                $theme->save();
                $m++;
                $theme->knowledges()->attach($kn->id);
                $section->knowledges()->attach($kn->id);
            }
            
        }
        $section = new StructureSection;
        $section->name = 'Итоговая аттестация';
        $section->position = $n;
        $section->st_version_id = $sv->id;
        $section->save();
    }
}
