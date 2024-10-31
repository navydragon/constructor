<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\StructureVersion;
use App\StructureSection;
use App\Dpp;
use App\Knowledge;
use App\Ability;
use App\Skill;

use Illuminate\Support\Facades\DB;
class StructureVersionController extends Controller
{
    public function show(Dpp $dpp, $sv)
    {

        if ($dpp->st_version_id == null) {
            $sv = $dpp->create_st();
        }else{
            $sv = StructureVersion::findOrFail($sv);
        }
        if ($dpp->dpp_type_id == 2) {
            $sv->recount_all_sections_pp();
        }

        if ($dpp->dpp_type_id == 1) {
            $sv->recount_section_hours_pk();
        }

        $sections = StructureSection::with(['knowledges' => function ($query) {}])
         ->with(['themes' => function ($query) {$query->orderBy('position');}])
         ->where('parent_id','=', null)
         ->where('st_version_id','=', $sv->id)
         ->orderBy('position')
         ->get();
         foreach ($sections as $section)
         {
             $section->zuns = (object)['knowledges'=>$section->knowledges,'abilities'=>$section->abilities,'skills'=>$section->skills];
             foreach ($section->themes as $theme)
             {
                $theme->zuns = (object)['knowledges'=>$theme->knowledges,'abilities'=>$theme->abilities,'skills'=>$theme->skills];
                $theme->lections = $theme->lections;
             }
         }

        return $sections;
    }

    public function zoons_show(StructureVersion $sv)
    {
        $dpp = Dpp::findOrFail($sv->dpp_id);
        $zoons = [];
        $knowledges = Knowledge::where('zun_version_id','=',$dpp->zun_version_id)
        ->with(['sections' => function ($query) {$query->where('parent_id','!=',null)->orderBy('position');}])
        ->get();
        $abilities = Ability::where('zun_version_id','=',$dpp->zun_version_id)
        ->with(['sections' => function ($query) {$query->where('parent_id','!=',null)->orderBy('position');}])
        ->get();
        $skills = Skill::where('zun_version_id','=',$dpp->zun_version_id)
        ->with(['sections' => function ($query) {$query->where('parent_id','!=',null)->orderBy('position');}])
        ->get();
        $zoons["knowledges"] = $knowledges;
        $zoons["abilities"] = $abilities;
        $zoons["skills"] = $skills;
        return json_encode($zoons);
    }
    public function rebuild(Dpp $dpp,StructureVersion $sv)
    {
        $res = $sv->rebuild();
    }

    public function recount_section_hours(StructureVersion $sv)
    {
        return $sv->recount_section_hours();
    }
}
