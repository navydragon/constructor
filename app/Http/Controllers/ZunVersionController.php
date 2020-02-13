<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Dpp;
use App\ZunVersion;
use App\Skill;
use App\Ability;
use App\Knowledge;
class ZunVersionController extends Controller
{
    public function add_skill(Dpp $dpp,ZunVersion $zv, Request $request)
    {
        $data = $request->skill;
        $skill = new Skill;
        $skill->dpp_id = $dpp->id;
        $skill->zun_version_id = $zv->id;
        $skill->name = $data["text"];
        $skill->keyword = $data["keyword"];
        $skill->what = $data["what"];
        $skill->with = $data["with"];
        $skill->where = $data["where"];
        $skill->save();
        foreach ($data["children"] as $abil){
            $ability = new Ability;
            $ability->dpp_id = $dpp->id;
            $ability->zun_version_id = $zv->id;
            $ability->name = $abil["text"];
            $ability->keyword = $abil["keyword"];
            $ability->what = $abil["what"];
            $ability->with = $abil["with"];
            $ability->where = $abil["where"];
            $ability->skill_id = $skill->id;
            $ability->has_parent_comp = false;
            $ability->save();
            foreach ($abil["children"] as $know){
                $knowledge = new Knowledge;
                $knowledge->dpp_id = $dpp->id;
                $knowledge->zun_version_id = $zv->id;
                $knowledge->name = $know["text"];
                $knowledge->keyword = $know["keyword"];
                $knowledge->what = $know["what"];
                $knowledge->with = " ";
                $knowledge->where = " ";
                $knowledge->ability_id = $ability->id;
                $knowledge->is_through = false;
                $knowledge->save();
            }
        }
        // return true;
    }

    public function add_ability(Dpp $dpp,ZunVersion $zv, Request $request)
    {
        $data = $request->ability;
        $ability = new Ability;
        $ability->dpp_id = $dpp->id;
        $ability->zun_version_id = $zv->id;
        $ability->name = $data["text"];
        $ability->keyword = $data["keyword"];
        $ability->what = $data["what"];
        $ability->with = $data["with"];
        $ability->where = $data["where"];
        $ability->skill_id = null;
        $ability->has_parent_comp = true;
        $ability->save();
        foreach ($data["children"] as $know){
            $knowledge = new Knowledge;
            $knowledge->dpp_id = $dpp->id;
            $knowledge->zun_version_id = $zv->id;
            $knowledge->name = $know["text"];
            $knowledge->keyword = $know["keyword"];
            $knowledge->what = $know["what"];
            $knowledge->with = " ";
            $knowledge->where = " ";
            $knowledge->ability_id = $ability->id;
            $knowledge->is_through = false;
            $knowledge->save();
        }
        // return true;
    }

    public function get_zun_version_data(Dpp $dpp,ZunVersion $zv)
    {
        $result = [];
        $skills = Skill::where('zun_version_id',$zv->id)->get();
        foreach ($skills as $skill)
        {
            $skill_row = [];
            $skill_row["text"] = $skill->name;
            $skill_row["id"] = 's_'.$skill->id;
            $skill_row["color"] = "btn-secondary";
            $skill_row["icon"] = "ion ion-ios-radio-button-on text-secondary";
            $skill_row["type"] = "skil";
            $skill_row["opened"] = true;
            $skill_row["keyword"] = $skill->keyword;
            $skill_row["what"] = $skill->what;
            $skill_row["with"] = $skill->with;
            $skill_row["where"] = $skill->where;
            $skill_row["children"] = [];
            $abilities = Ability::where('skill_id',$skill->id)->get();
            foreach ($abilities as $ability)
            {
                $ability_row = [];
                $ability_row["text"] = $ability->name;
                $ability_row["id"] = 'a_'.$ability->id;
                $ability_row["color"] = "btn-success";
                $ability_row["icon"] = "ion ion-ios-radio-button-on text-success";
                $ability_row["type"] = "abil";
                $ability_row["opened"] = true;
                $ability_row["keyword"] = $ability->keyword;
                $ability_row["what"] = $ability->what;
                $ability_row["with"] = $ability->with;
                $ability_row["where"] = $ability->where;
                $ability_row["children"] = [];
                $knowledges = Knowledge::where('ability_id',$ability->id)->get();
                foreach ($knowledges as $knowledge)
                {
                    $knowledge_row = [];
                    $knowledge_row["text"] = $knowledge->name;
                    $knowledge_row["id"] = 'k_'.$knowledge->id;
                    $knowledge_row["color"] = "btn-warning";
                    $knowledge_row["icon"] = "ion ion-ios-radio-button-on text-warning";
                    $knowledge_row["type"] = "know";
                    $knowledge_row["opened"] = true;
                    $knowledge_row["keyword"] = $knowledge->keyword;
                    $knowledge_row["what"] = $knowledge->what;
                    $knowledge_row["with"] = $knowledge->with;
                    $knowledge_row["where"] = $knowledge->where;
                    array_push($ability_row["children"],$knowledge_row);                    
                }
                array_push($skill_row["children"],$ability_row);
            }
            array_push($result,$skill_row);
        }
        $abilities = Ability::where('zun_version_id',$zv->id)
        ->where('skill_id',null)->get();
        foreach ($abilities as $ability)
            {
                $ability_row = [];
                $ability_row["text"] = $ability->name;
                $ability_row["id"] = 'a_'.$ability->id;
                $ability_row["color"] = "btn-success";
                $ability_row["icon"] = "ion ion-ios-radio-button-on text-success";
                $ability_row["type"] = "abil";
                $ability_row["opened"] = true;
                $ability_row["keyword"] = $ability->keyword;
                $ability_row["what"] = $ability->what;
                $ability_row["with"] = $ability->with;
                $ability_row["where"] = $ability->where;
                $ability_row["children"] = [];
                $knowledges = Knowledge::where('ability_id',$ability->id)->get();
                foreach ($knowledges as $knowledge)
                {
                    $knowledge_row = [];
                    $knowledge_row["text"] = $knowledge->name;
                    $knowledge_row["id"] = 'k_'.$knowledge->id;
                    $knowledge_row["color"] = "btn-warning";
                    $knowledge_row["icon"] = "ion ion-ios-radio-button-on text-warning";
                    $knowledge_row["type"] = "know";
                    $knowledge_row["opened"] = true;
                    $knowledge_row["keyword"] = $knowledge->keyword;
                    $knowledge_row["what"] = $knowledge->what;
                    $knowledge_row["with"] = $knowledge->with;
                    $knowledge_row["where"] = $knowledge->where;
                    array_push($ability_row["children"],$knowledge_row);                    
                }
                array_push($result,$ability_row);
            }
        return json_encode($result);
    }
}
