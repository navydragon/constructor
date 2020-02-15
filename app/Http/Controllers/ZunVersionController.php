<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Dpp;
use App\ZunVersion;
use App\Skill;
use App\Ability;
use App\Knowledge;
use App\Competence;
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

    public function add_competence(Dpp $dpp,ZunVersion $zv, Request $request)
    {
        $data = $request->competence;
        $comp = new Competence;
        $comp->dpp_id = $dpp->id;
        $comp->zun_version_id = $zv->id;
        $comp->name = $data["text"];
        $comp->keyword = $data["keyword"];
        $comp->what = $data["what"];
        $comp->with = $data["with"];
        $comp->where = $data["where"];
        $comp->save();
        foreach ($data["selected"] as $item)
        {
            $id = $item;
            $id2 = explode("_",$id);
            if ($id2[0]=='s')
            { 
                $sk = Skill::find((int)$id2[1]);
                $sk->competence_id = $comp->id;
                $sk->save();
            }
            if ($id2[0]=='a')
            { 
                $ab = Ability::find((int)$id2[1]);
                $ab->competence_id = $comp->id;
                $ab->save();
            }
        }
    }

    public function get_zun_version_data_unattached(Dpp $dpp,ZunVersion $zv)
    {
        $result = [];
        $skills = Skill::where('zun_version_id',$zv->id)->where('competence_id',null)->get();
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
            $skill_row["valid"] = true;
            $skill_row["is_main"] = true;
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
                $ability_row["valid"] = true;
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
                    $knowledge_row["valid"] = true;
                    $knowledge_row["children"] = [];
                    array_push($ability_row["children"],$knowledge_row);                    
                }
                array_push($skill_row["children"],$ability_row);
            }
            array_push($result,$skill_row);
        }
        $abilities = Ability::where('zun_version_id',$zv->id)
        ->where('skill_id',null)->where('competence_id',null)->get();
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
                $ability_row["valid"] = true;
                $ability_row["is_main"] = true;
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
                    $knowledge_row["children"] = [];
                    $knowledge_row["valid"] = true;
                    array_push($ability_row["children"],$knowledge_row);                    
                }
                array_push($result,$ability_row);
            }
        return json_encode($result);
    }

    public function get_zun_version_data_attached(Dpp $dpp,ZunVersion $zv)
    {
        $result = [];
        $competences = Competence::where('zun_version_id',$zv->id)->get();
        foreach ($competences as $competence)
        {
            $competence_row = [];
            $competence_row = [];
            $competence_row["text"] = $competence->name;
            $competence_row["id"] = 'c_'.$competence->id;
            $competence_row["color"] = "btn-primary";
            $competence_row["icon"] = "ion ion-ios-radio-button-on text-primary";
            $competence_row["type"] = "comp";
            $competence_row["opened"] = true;
            $competence_row["is_main"] = true;
            $competence_row["keyword"] = $competence->keyword;
            $competence_row["what"] = $competence->what;
            $competence_row["with"] = $competence->with;
            $competence_row["where"] = $competence->where;
            $competence_row["valid"] = true;
            $competence_row["children"] = [];
            $skills = Skill::where('zun_version_id',$zv->id)->where('competence_id',$competence->id)->get();
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
                $skill_row["valid"] = true;
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
                    $ability_row["valid"] = true;
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
                        $knowledge_row["valid"] = true;
                        $knowledge_row["children"] = [];
                        array_push($ability_row["children"],$knowledge_row);                    
                    }
                    array_push($skill_row["children"],$ability_row);
                }
                array_push($competence_row["children"],$skill_row);
            }
            
            $abilities = Ability::where('zun_version_id',$zv->id)
        ->where('skill_id',null)->where('competence_id',$competence->id)->get();
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
                $ability_row["valid"] = true;
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
                    $knowledge_row["children"] = [];
                    $knowledge_row["valid"] = true;
                    array_push($ability_row["children"],$knowledge_row);                    
                }
                array_push($competence_row["children"],$ability_row);
            }
            array_push($result,$competence_row);
        }
        return json_encode($result);
    }

    public function delete_knowledge(Request $request)
    {
        $id = $request->id;
        $id = explode("_",$id);
        $id = $id[1];
        Knowledge::destroy($id);
    }

    public function delete_ability(Request $request)
    {
        $id = $request->id;
        $id = explode("_",$id);
        $id = $id[1];
        $kns = Knowledge::where('ability_id',$id)->get();
        foreach($kns as $kn)
        {
            Knowledge::destroy($kn->id);
        }
        Ability::destroy($id);
    }

    public function delete_skill(Request $request)
    {
        $id = $request->id;
        $id = explode("_",$id);
        $id = $id[1];
        $abs = Ability::where('skill_id',$id)->get();
        foreach ($abs as $ab)
        {
            $kns = Knowledge::where('ability_id',$ab->id)->get();
            foreach($kns as $kn)
            {
                Knowledge::destroy($kn->id);
            }
            Ability::destroy($ab->id);
        }
        Skill::destroy($id);
    }

    public function delete_competence (Request $request)
    {
        $id = $request->id;
        $id = explode("_",$id);
        $id = $id[1];

        $sks = Skill::where('competence_id',$id)->get();
        foreach ($sks as $sk)
        {
            $request->id = 's_'.$sk->id;
            $this->delete_skill($request);
        }

        $abs = Ability::where('competence_id',$id)->get();
        foreach ($abs as $ab)
        {
            $request->id = 'a_'.$ab->id;
            $this->delete_ability($request);
        }

        Competence::destroy($id);
    }

    public function update_elem(Dpp $dpp,ZunVersion $zv,Request $request)
    {
        $item = $request->item;
        $id = $item["id"];
        $id2 = explode("_",$id);
        if ($id2[0]=='s')
        {
            $request->id = $id;
            $this->delete_skill($request);
            $request->skill = $request->item;
            $this->add_skill($dpp,$zv,$request);
        }
        if ($id2[0]=='a')
        {
            $request->id = $id;
            $this->delete_ability($request);
            $request->ability = $request->item;
            $this->add_ability($dpp,$zv,$request);
        }
    }
}
