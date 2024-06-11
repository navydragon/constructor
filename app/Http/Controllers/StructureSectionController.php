<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Dpp;
use App\StructureVersion;
use App\StructureSection;
use App\Knowledge;
use App\Ability;
use App\Skill;


function section ($id)
{
    $section = StructureSection::with(['themes' => function ($query) {$query->orderBy('position');}])
        ->where('id','=', $id)
        ->orderBy('position')
        ->get()->first();
    $section->zuns = (object)['knowledges'=>$section->knowledges,'abilities'=>$section->abilities,'skills'=>$section->skills];
    foreach ($section->themes as $theme)
    {
        $theme->zuns = (object)['knowledges'=>$theme->knowledges,'abilities'=>$theme->abilities,'skills'=>$theme->skills];
    }
    return $section;
}

class StructureSectionController extends Controller
{
    public function recount_section($section_id)
    {
        $section = StructureSection::find($section_id);

        $section->lection_hours = 0; $section->lection_hours_o = 0; $section->lection_hours_z = 0;
        $section->practice_hours = 0; $section->practice_hours_o = 0; $section->practice_hours_z = 0;
        # $section->consult_hours = 0;  $section->consult_hours_o = 0; $section->consult_hours_z = 0;
        $section->self_hours = 0; $section->lab_hours = 0;
        $section->total_hours = 0;

        foreach ($section->themes as $theme)
        {
            $section->lection_hours += $theme->lection_hours;
            $section->lection_hours_o += $theme->lection_hours_o;
            $section->lection_hours_z += $theme->lection_hours_z;

            $section->practice_hours += $theme->practice_hours;
            $section->practice_hours_o += $theme->practice_hours_o;
            $section->practice_hours_z += $theme->practice_hours_z;

            $section->consult_hours += $theme->consult_hours;
            $section->consult_hours_o += $theme->consult_hours_o;
            $section->consult_hours_z += $theme->consult_hours_z;

            $section->lab_hours += $theme->lab_hours;

            $section->self_hours += $theme->self_hours;
            $section->total_hours += $theme->total_hours;

            // $section->attestation_hours += $theme->attestation_hours;
        }
        $section->total_hours += $section->attestation_hours;
        $section->total_hours += $section->consult_hours;
        $section->save();
    }

    public function update(StructureVersion $sv,$id, Request $request)
    {
        if ($sv->dpp->dpp_type_id == 2) {
            return $this->update_pp($sv,$id,$request);
        }
        $section = $request->section;
        if ($section["parent_id"] != "")
        {
            $theme = StructureSection::findOrFail($id);
            $theme->name = $request->section["name"];
            $theme->lection_hours = $request->section["lection_hours"];
            $theme->practice_hours = $request->section["practice_hours"];
            $theme->self_hours = $request->section["self_hours"];
            $theme->lab_hours = $request->section["lab_hours"];
            $theme->attestation_hours = $request->section["attestation_hours"];
            $theme->total_hours = $request->section["lection_hours"] + $request->section["practice_hours"] +  $request->section["self_hours"] + $request->section["lab_hours"] + $request->section["attestation_hours"];
            $theme->save();
            if ($theme->practice_hours > 0 || $theme->lab_hours > 0)
            {
                $section = StructureSection::find($theme->parent_id);
                $knowledges = $theme->knowledges;
                foreach ($knowledges as $knowledge)
                {
                    if ($knowledge->ability_id != null)
                    {
                        $ability = Ability::find($knowledge->ability_id);
                        $theme->abilities()->syncWithoutDetaching($ability->id);
                        $section->abilities()->syncWithoutDetaching($ability->id);
                    }
                    if ($ability->skill_id != null)
                    {
                        $theme->skills()->syncWithoutDetaching($ability->skill_id);
                        $section->skills()->syncWithoutDetaching($ability->skill_id);
                    }
                }
            }
            if ($theme->practice_hours == 0 && $theme->lab_hours == 0)
            {
                $section = StructureSection::find($theme->parent_id);
                $knowledges = $theme->knowledges;
                foreach ($knowledges as $knowledge)
                {
                    if ($knowledge->ability_id != null)
                    {
                        $ability = Ability::find($knowledge->ability_id);
                        $theme->abilities()->detach($ability->id);
                        $section->abilities()->detach($ability->id);
                        if ($ability->skill_id != null)
                        {
                            $theme->skills()->detach($ability->skill_id);
                            $section->skills()->detach($ability->skill_id);
                        }
                    }

                }
            }

            $this->recount_section($theme->parent_id);

            $section = StructureSection::with(['themes' => function ($query) {$query->orderBy('position');}])
            ->where('id','=', $theme->parent_id)
            ->orderBy('position')
            ->get()->first();
            $section->zuns = (object)['knowledges'=>$section->knowledges,'abilities'=>$section->abilities,'skills'=>$section->skills];
            foreach ($section->themes as $theme)
            {
            $theme->zuns = (object)['knowledges'=>$theme->knowledges,'abilities'=>$theme->abilities,'skills'=>$theme->skills];
            }
            return $section;
        }else{
            $sect = StructureSection::findOrFail($id);
            if ($sect->name == 'Итоговая аттестация')
            {
                $sect->attestation_hours = $request->section["attestation_hours"];
                $sect->total_hours = $request->section["attestation_hours"];
            }else{
                $sect->name = $request->section["name"];
            }
            $sect->save();
            return section ($id);
        }

    }

    public function reorder(StructureVersion $sv,$id, Request $request)
    {
        $themes = $request->themes;
        $position = 1;
        foreach ($themes as $id)
        {
            $theme = StructureSection::where('id',$id)->first();
            $theme->position = $position;
            $theme->save();
            $knowledge = Knowledge::find($theme->knowledge_id);
            $knowledge->dtps()->updateExistingPivot($theme->dtp_id, array('position' => $position), false);
            $position++;
        }

        return response()->json(['message'=>'success'],200);
    }

    function update_pp(StructureVersion $sv, $id, Request $request)
    {

        $section = StructureSection::findOrFail($id);
        $l_o = $request->section["lection_hours_o"]; $l_z = $request->section["lection_hours_z"];
        $p_o = $request->section["practice_hours_o"]; $p_z = $request->section["practice_hours_z"];
        $c_o = $request->section["consult_hours_o"]; $c_z = $request->section["consult_hours_z"];

        if (!is_null($section->parent_id) || $section->themes->count() == 0) {
            $section->lection_hours_o = $l_o;
            $section->lection_hours_z = $l_z;
            $section->lection_hours = $l_o + $l_z;
            $section->practice_hours_o = $p_o;
            $section->practice_hours_z = $p_z;
            $section->practice_hours = $p_o + $p_z;
        }

        $section->consult_hours_o = $c_o;
        $section->consult_hours_z = $c_z;
        $section->consult_hours = $c_o + $c_z;
        $section->attestation_hours = $request->section["attestation_hours"];
        $section->name = $request->section["name"];
        $section->attestation_form = $request->section["attestation_form"];
        $section->total_hours = $section->lection_hours + $section->consult_hours + $section->attestation_hours + $section->practice_hours;
        $section->save();
        if ($section->parent_id != null)
        {
            $this->recount_section($section->parent_id);
        }
        if ($section->parent_id != null) {

            return  section ($section->parent_id);
        }
        return section ($id);
    }
    public function get_sections(Dpp $dpp, StructureVersion $sv)
    {

         //$sections = $sv->get_sections->where('parent_id','=', null);
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

    public function get_zuns(Dpp $dpp, StructureVersion $sv)
    {
        $res = [];
        $knowledges = Knowledge::where('zun_version_id','=',$dpp->zun_version_id)
        ->with(['sections' => function ($query) {$query->orderBy('position');}])
        ->get();
        $abilities = Ability::where('zun_version_id','=',$dpp->zun_version_id)
        ->with(['sections' => function ($query) {$query->orderBy('position');}])
        ->get();
        $skills = Skill::where('zun_version_id','=',$dpp->zun_version_id)
        ->with(['sections' => function ($query) {$query->orderBy('position');}])
        ->get();
        $res["knowledges"] = $knowledges;
        $res["abilities"] = $abilities;
        $res["skills"] = $skills;
        return json_encode($res);
    }

    public function add_theme(Dpp $dpp, StructureVersion $sv, Request $request)
    {
        $secs_count = StructureSection::where('parent_id','=',$request->theme_data["parent_id"])->get()->count();
        $theme = new StructureSection;
        $theme->name = $request->theme_data["name"];
        $theme->lection_hours = $request->theme_data["lection_hours"];
        $theme->practice_hours = $request->theme_data["practice_hours"];
        $theme->self_hours = $request->theme_data["self_hours"];
        $theme->lab_hours = $request->theme_data["lab_hours"];
        $theme->attestation_hours = $request->theme_data["attestation_hours"];
        $theme->total_hours = $request->theme_data["lection_hours"] + $request->theme_data["practice_hours"] +  $request->theme_data["self_hours"] + $request->theme_data["lab_hours"] + $request->theme_data["attestation_hours"];
        $theme->position = $secs_count+1;
        $theme->st_version_id = $sv->id;
        $theme->is_blocked = false;
        $theme->parent_id = $request->theme_data["parent_id"];
        $theme->save();
        $theme->knowledges()->attach($request->theme_data["knowledges"]);
        $section = StructureSection::find($theme->parent_id);
        $section->lection_hours += $theme->lection_hours;
        $section->practice_hours += $theme->practice_hours;
        $section->self_hours += $theme->self_hours;
        $section->total_hours += $theme->total_hours;
        $section->lab_hours += $theme->lab_hours;
        $section->attestation_hours += $theme->attestation_hours;
        $section->save();
        $section->knowledges()->attach($request->theme_data["knowledges"]);
        $section = StructureSection::with(['themes' => function ($query) {$query->orderBy('position');}])
         ->where('id','=', $request->theme_data["parent_id"])
         ->orderBy('position')
         ->get()->first();
        $section->zuns = (object)['knowledges'=>$section->knowledges,'abilities'=>$section->abilities,'skills'=>$section->skills];
        foreach ($section->themes as $theme)
        {
        $theme->zuns = (object)['knowledges'=>$theme->knowledges,'abilities'=>$theme->abilities,'skills'=>$theme->skills];
        }

        return $section;
    }

    public function add_section(Dpp $dpp, StructureVersion $sv, Request $request)
    {
        $secs_count = StructureSection::where('parent_id','=',null)
        ->where('st_version_id','=',$sv->id)
        ->get()->count();
        $section = new StructureSection;
        $section->name = $request->section_data["name"];
        $section->lection_hours = 0;
        $section->practice_hours = 0;
        $section->self_hours = 0;
        $section->lab_hours = 0;
        $section->attestation_hours = 0;
        $section->total_hours = 0;
        $section->position = $secs_count+1;
        $section->st_version_id = $sv->id;
        $section->is_blocked = false;
        $section->save();
        $section = StructureSection::with(['themes' => function ($query) {$query->orderBy('position');}])
         ->where('id','=', $section->id)
         ->orderBy('position')
         ->get()->first();
        $section->zuns = (object)['knowledges'=>$section->knowledges,'abilities'=>$section->abilities,'skills'=>$section->skills];
        foreach ($section->themes as $theme)
        {
        $theme->zuns = (object)['knowledges'=>$theme->knowledges,'abilities'=>$theme->abilities,'skills'=>$theme->skills];
        }
        return $section;
    }

    public function update_section(Dpp $dpp, StructureVersion $sv, Request $request)
    {
        $section = StructureSection::find($request->section_data["id"]);
        $section->name = $request->section_data["name"];
        $section->lection_hours = $request->section_data["lection_hours"];
        $section->practice_hours = $request->section_data["practice_hours"];
        $section->self_hours = $request->section_data["self_hours"];
        $section->lab_hours = $request->section_data["lab_hours"];
        $section->attestation_hours = $request->section_data["attestation_hours"];
        $section->total_hours = $request->section_data["lection_hours"] + $request->section_data["practice_hours"] +  $request->section_data["self_hours"] + $request->section_data["lab_hours"] + $request->section_data["attestation_hours"];
        $section->save();
        $section = StructureSection::with(['themes' => function ($query) {$query->orderBy('position');}])
         ->where('id','=', $request->section_data["id"])
         ->orderBy('position')
         ->get()->first();
        $section->zuns = (object)['knowledges'=>$section->knowledges,'abilities'=>$section->abilities,'skills'=>$section->skills];
        foreach ($section->themes as $theme)
        {
        $theme->zuns = (object)['knowledges'=>$theme->knowledges,'abilities'=>$theme->abilities,'skills'=>$theme->skills];
        }
        return $section;

    }

    public function delete_theme(Dpp $dpp, StructureVersion $sv, Request $request)
    {
        $theme = StructureSection::find($request->id);
        $theme->knowledges()->detach();
        $below_themes = StructureSection::where('position','>',$theme->position)->where('parent_id','=',$theme->parent_id)->get();
        foreach ($below_themes as $bt)
        {
            $bt->position = $bt->position - 1;
            $bt->save();
        }
        StructureSection::destroy($request->id);
        $section = StructureSection::find($theme->parent_id);
        $section->lection_hours -= $theme->lection_hours;
        $section->practice_hours -= $theme->practice_hours;
        $section->self_hours -= $theme->self_hours;
        $section->total_hours -= $theme->total_hours;
        $section->lab_hours -= $theme->lab_hours;
        $section->attestation_hours -= $theme->attestation_hours;
        $section->save();
        $section = StructureSection::with(['knowledges' => function ($query) {}])
         ->with(['themes' => function ($query) {$query->orderBy('position');}])
         ->where('id','=', $section->id)
         ->orderBy('position')
         ->get()->first();
        return $section;
    }
    public function delete_section(Dpp $dpp, StructureVersion $sv, Request $request)
    {
        $section = StructureSection::find($request->id);
        foreach ($section->themes as $theme)
        {
            $theme = StructureSection::find($theme->id);
            $theme->knowledges()->detach();
            StructureSection::destroy($theme->id);
        }
        $section->knowledges()->detach();
        StructureSection::destroy($request->id);
        return 'success';
    }



    public function move_up(StructureVersion $sv, $section, Request $request)
    {
        $section = StructureSection::find($section);

        $pos = $section->position;
        $changer = StructureSection::where('st_version_id','=',$sv->id)
        ->where('parent_id','=',$section->parent_id)
        ->where('position','=',$pos-1)
        ->get()->first();

        $section->position = $changer->position;
        $changer->position = $pos;
        $section->save();
        $changer->save();
        $section = StructureSection::with(['knowledges' => function ($query) {}])
                ->with(['themes' => function ($query) {$query->orderBy('position');}])
                ->where('id','=', $section->parent_id)
                ->orderBy('position')
                ->get()->first();
        return json_encode($section);

    }

    public function move_down(StructureVersion $sv, $section, Request $request)
    {
        $section = StructureSection::find($section);
        $pos = $section->position;
        $changer = StructureSection::where('st_version_id','=',$sv->id)
        ->where('parent_id','=',$section->parent_id)
        ->where('position','=',$pos+1)
        ->get()->first();
        $section->position = $changer->position;
        $changer->position = $pos;
        $section->save();
        $changer->save();
        $section = StructureSection::with(['knowledges' => function ($query) {}])
                ->with(['themes' => function ($query) {$query->orderBy('position');}])
                ->where('id','=', $section->parent_id)
                ->orderBy('position')
                ->get()->first();
        return $section;
    }
}
