<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Dpp;
use App\ZunVersion;
use App\IshVersion;
use App\Typology;
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

    public function create_competence(Dpp $dpp,ZunVersion $zv, Request $request)
    {
        $data = $request->competence;
        $competence = new Competence;
        $competence->dpp_id = $dpp->id;
        $competence->zun_version_id = $zv->id;
        $competence->name = $data["text"];
        $competence->keyword = $data["keyword"];
        $competence->what = $data["what"];
        $competence->with = $data["with"];
        $competence->where = $data["where"];
        $competence->save();

        foreach ($data["children"] as $elem){
            if ($elem["type"] == 'skil')
            {
                $skill = new Skill;
                $skill->dpp_id = $dpp->id;
                $skill->zun_version_id = $zv->id;
                $skill->name = $elem["text"];
                $skill->keyword = $elem["keyword"];
                $skill->what = $elem["what"];
                $skill->with = $elem["with"];
                $skill->where = $elem["where"];
                $skill->competence_id = $competence->id;
                $skill->save();
                foreach ($elem["children"] as $abil){
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
            }
            if ($elem["type"] == 'abil')
            {
                $ability = new Ability;
                $ability->dpp_id = $dpp->id;
                $ability->zun_version_id = $zv->id;
                $ability->name = $elem["text"];
                $ability->keyword = $elem["keyword"];
                $ability->what = $elem["what"];
                $ability->with = $elem["with"];
                $ability->where = $elem["where"];
                $ability->skill_id = null;
                $ability->has_parent_comp = true;
                $ability->competence_id = $competence->id;
                $ability->save();
                foreach ($elem["children"] as $know){
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
        }
        // return true;
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

        if ($id2[0]=='c')
        {
            $request->id = $id;
            $this->delete_competence($request);
            $request->competence = $request->item;
            $this->create_competence($dpp,$zv,$request);
        }
    }

    /* version 2 */
    public function get_zun_version_data2(Dpp $dpp,ZunVersion $zv)
    {
        $result = [];
        $competences = Competence::where('zun_version_id',$zv->id)->get();
        $skills = Skill::where('zun_version_id',$zv->id)->get();
        $abilities = Ability::where('zun_version_id',$zv->id)->get();
        $knowledges = Knowledge::where('zun_version_id',$zv->id)->where('is_through',false)->get();
        $th_knowledges = Knowledge::where('zun_version_id',$zv->id)->where('is_through',true)->get();
        foreach ($competences as $competence)
        {
            $row = [];
            $row["id"] = 'c'.$competence->id;
            $row["name"] = $competence->name;
            $row["pid"] = null;
            $row["type"] = "Компетенция";
            array_push($result,$row);                    
        }
        foreach ($skills as $skill)
        {
            $row = [];
            $row["id"] = 's'.$skill->id;
            $row["name"] = $skill->name;
            $row["pid"] = 'c'.$skill->competence_id;
            $row["type"] = "Навык";
            array_push($result,$row);                    
        }
        foreach ($abilities as $ability)
        {
            $row = [];
            $row["id"] = 'a'.$ability->id;
            $row["name"] = $ability->name;
            if ($ability->has_parent_comp == true)
            {
                $row["pid"] = 'c'.$ability->competence_id;
            }else{
                $row["pid"] = 's'.$ability->skill_id;
            }
            $row["type"] = "Умение";
            array_push($result,$row);                    
        }
        foreach ($knowledges as $knowledge)
        {
            $row = [];
            $row["id"] = 'k'.$knowledge->id;
            $row["name"] = $knowledge->name;
            $row["pid"] = 'a'.$knowledge->ability_id;
            $row["type"] = "Знание";
            array_push($result,$row);                    
        }
        foreach ($th_knowledges as $knowledge)
        {
            $row = [];
            $row["id"] = 'k'.$knowledge->id;
            $row["name"] = $knowledge->name;
            $row["pid"] = 0;
            $row["type"] = "Знание";
            array_push($result,$row);                    
        }
        $row = [];
        $row["id"] = 0;
        $row["name"] = 'СКВОЗНЫЕ ЗНАНИЯ';
        $row["pid"] = null;
        $row["type"] = "Сквозные знания";
        array_push($result,$row);
        return json_encode($result);
    }

    public function add_competence2(Dpp $dpp,ZunVersion $zv, Request $request)
    {
        $data = $request->competence_data; 
        $comp = new Competence;
        $comp->dpp_id = $dpp->id;
        $comp->zun_version_id = $request->zun_version;
        $comp->name = $request->competence_name;
        $comp->keyword = $data["keyword"];
        $comp->what = $data["what"];
        $comp->with = $data["with"];
        $comp->where = $data["where"];
        $comp->save();
        foreach ($data["elems"] as $elem)
        {
            $elem = substr($elem,1);
            $sk = Skill::find($elem);
            if ($sk)
            {
                $sk->competence_id = $comp->id;
                $sk->save();
            }else{
                $ab = Ability::find($elem);
                $ab->competence_id = $comp->id;
                $ab->has_parent_comp = true;
                $ab->save();
            }
        }
        $comp->new_id='c'.$comp->id;
        return $comp;
    }

    public function remove_competence2(Request $request)
    {
        $id = substr($request->competence_id,1); 
        Competence::destroy($id);
    }

    public function add_skill2(Dpp $dpp,ZunVersion $zv, Request $request)
    {
        $data = $request->skill_data;
        if ($request->parent_node != '')
        { $parent_node = substr($request->parent_node,1); 
        }else { $parent_node = null;}
        $skill = new Skill;
        $skill->dpp_id = $dpp->id;
        $skill->zun_version_id = $request->zun_version;
        $skill->name = $request->skill_name;
        $skill->keyword = $data["keyword"];
        $skill->what = $data["what"];
        $skill->with = $data["with"];
        $skill->where = $data["where"];
        $skill->competence_id = $parent_node;
        $skill->save();
        $skill->nsis()->sync($data["nsis"]);
        $skill->new_id = 's'.$skill->id;
        $skill->new_parent = $request->parent_node;
        return $skill;
    }

    public function remove_skill2 (Request $request)
    {
        $id = substr($request->skill_id,1); 
        $sk = Skill::find($id);
        $sk->nsis()->detach();
        Skill::destroy($id);
    }

    public function update_skill2  (Dpp $dpp, Request $request)
    {
        $data = $request->skill_data;
        $skill = Skill::find($data["id"]);

        $skill->name = $request->skill_name;
        $skill->keyword = $data["keyword"];
        $skill->what = $data["what"];
        $skill->with = $data["with"];
        $skill->where = $data["where"];
        $skill->save();
        $skill->nsis()->sync($data["nsis"]);
        $skill->new_id = 's'.$skill->id;
        return $skill;
    }

    public function add_ability2(Dpp $dpp,ZunVersion $zv, Request $request)
    {
        $data = $request->ability_data;
        if ($request->parent_node != '')
        { $parent_node = substr($request->parent_node,1); 
        }else { $parent_node = null;} 
        $ability = new Ability;
        $ability->dpp_id = $dpp->id;
        $ability->zun_version_id = $request->zun_version;
        $ability->name = $request->ability_name;
        $ability->keyword = $data["keyword"];
        $ability->what = $data["what"];
        $ability->with = $data["with"];
        $ability->where = $data["where"];
        if ($request->parent_type == 'no')
        {
            $ability->has_parent_comp = false;
        }
        if ($request->parent_type == 'skill')
        {
            $ability->has_parent_comp = false;
            $ability->skill_id = $parent_node;
        }
        if ($request->parent_type == 'competence')
        {
            $ability->has_parent_comp = true;
            $ability->competence_id = $parent_node;
        }
        $ability->save();
        $ability->nsis()->sync($data["nsis"]);
        $ability->new_id = 'a'.$ability->id;
        $ability->new_parent = $request->parent_node;
        return $ability;
    }

    public function remove_ability2 (Request $request)
    {
        $id = substr($request->ability_id,1); 
        $ab = Ability::find($id);
        $ab->nsis()->detach();
        Ability::destroy($id);
    }

    public function update_ability2  (Dpp $dpp, Request $request)
    {
        $data = $request->ability_data;
        $ability = Ability::find($data["id"]);

        $ability->name = $request->ability_name;
        $ability->keyword = $data["keyword"];
        $ability->what = $data["what"];
        $ability->with = $data["with"];
        $ability->where = $data["where"];
        $ability->save();
        $ability->nsis()->sync($data["nsis"]);
        $ability->new_id = 'a'.$ability->id;
        return $ability;
    }

    public function add_knowledge2(Dpp $dpp,ZunVersion $zv, Request $request)
    {
        $data = $request->knowledge_data; 
        $knowledge = new Knowledge;
        $knowledge->dpp_id = $dpp->id;
        $knowledge->zun_version_id = $request->zun_version;
        $knowledge->name = $request->knowledge_name;
        $knowledge->keyword = $data["keyword"];
        $knowledge->what = $data["what"];
        $knowledge->with = " ";
        $knowledge->where = " ";
        if ($request->parent_node == '0')
        {
            $knowledge->is_through = true;
            $knowledge->ability_id = null;
        }else{
            $knowledge->is_through = false;
            $parent_node = substr($request->parent_node,1); 
            $knowledge->ability_id = $parent_node;
        }

        $knowledge->save();        
        $knowledge->nsis()->sync($data["nsis"]);
        $knowledge->new_id = 'k'.$knowledge->id;
        $knowledge->new_parent = $request->parent_node;
        return $knowledge;
    }

    public function remove_knowledge2 (Request $request)
    {
        $id = substr($request->knowledge_id,1); 
        $kn = Knowledge::find($id);
        $kn->nsis()->detach();
        Knowledge::destroy($id);
    }

    public function update_knowledge2 (Dpp $dpp, Request $request)
    {
        $data = $request->knowledge_data;
        $knowledge = Knowledge::find($data["id"]);
        $knowledge->name = $request->knowledge_name;
        $knowledge->what = $data["what"];
        $knowledge->save();
        $knowledge->nsis()->sync($data["nsis"]);
        $knowledge->new_id = 'k'.$knowledge->id;
        return $knowledge;
    }

    public function add_knowledge_link2(Dpp $dpp, Request $request)
    {
        $knowledge_id = substr($request->knowledge_id,1); 
        $ability_id = substr($request->ability_id,1);
        $kn = Knowledge::find($knowledge_id);
        $kn->links()->attach($ability_id);
    }

    public function move_elem2(Request $request)
    {
        $elem_type = $request->elem_type; 
        $elem_id = substr($request->elem_id,1);
         
        $to_type = $request->to_type;
        $to_id = substr($request->to_id,1);

        switch ($elem_type) {
            case 'Знание':
                $kn = Knowledge::find($elem_id);
                if ($to_type == 'Сквозные знания')
                {
                    $kn->ability_id = null;
                    $kn->is_through = true;
                }

                if ($to_type == 'Умение') 
                {
                    $kn->ability_id = $to_id;
                }
                $kn->save();
            break;

            case 'Навык':
                $sk = Skill::find($elem_id);
                $sk->competence_id = $to_id;
                $sk->save();
            break;

            case 'Умение':
                $ab = Ability::find($elem_id);
                if ($to_type == 'Компетенция')
                {
                    $ab->has_parent_comp = true;
                    $ab->skill_id = null;
                    $ab->competence_id = $to_id;
                }
                if ($to_type == 'Навык')
                {
                    $ab->has_parent_comp = false;
                    $ab->skill_id = $to_id;
                    $ab->competence_id = null;
                }                
                $ab->save();
            break;
            
            default:
            break;
        }
    }

    public function disconnect2(Request $request)
    {
        $el = $request->elem;
        $el["id"] = substr($el["id"],1);
        switch ($el["type"]) {
            case 'Знание':
                $kn = Knowledge::find($el["id"]);
                $kn->ability_id = null;
                $kn->is_through = false;
                $kn->save();
            break;
            case 'Умение':
                $ab = Ability::find($el["id"]);
                if ($ab->has_parent_comp == true)
                {
                    $ab->competence_id = null;
                }else{
                    $ab->skill_id = null;
                }
                $ab->save();
            break;
            
            case 'Навык':
                $sk = Skill::find($el["id"]);
                $sk->competence_id = null;
                $sk->save();
            break;

            default:
                # code...
                break;
        }
    }

    public function get_links (Dpp $dpp,ZunVersion $zv)
    {
        $result = [];
        $knowledges = Knowledge::where('zun_version_id',$zv->id)->where('is_through',false)->get();
        foreach ($knowledges as $knowledge)
        {
            $links = $knowledge->links()->get();
            if (count($links) > 0) {
             //   dd($links);
            }
            foreach ($links as $link)
            {
                $row = [];
                $row["from"] = 'k'.$knowledge->id;
                $row["to"] = 'a'.$link->id;
                $row["label"] = '';
                $row["template"] = 'blue';
                array_push($result,$row);
            }
        }
        return json_encode($result);
    }

    public function get_typology(Dpp $dpp)
    {
        $iv = IshVersion::find($dpp->ish_version_id);
        $tl = Typology::find($iv->typology_id);
        return json_encode($tl->typology_parts);
    }

    public function get_skill_info($sk)
    {
        $arr = [];
        $id = substr($sk,1);
        $sk = Skill::find($id);
        $nsis = $sk->nsis()->get();
        foreach ($nsis as $nsi)
        {
            array_push($arr,$nsi->id);
        }
        $sk->nsis = $arr;
        $sk->valid = true;
        //dd($sk->nsis);
        return $sk;
    }

    public function get_ability_info($ab)
    {
        $arr = [];
        $id = substr($ab,1);
        $ab = Ability::find($id);
        $nsis = $ab->nsis()->get();
        foreach ($nsis as $nsi)
        {
            array_push($arr,$nsi->id);
        }
        $ab->nsis = $arr;
        $ab->valid = true;
        //dd($sk->nsis);
        return $ab;
    }

    public function get_knowledge_info($kn)
    {
        $arr = [];
        $id = substr($kn,1);
        $kn = Knowledge::find($id);
        $nsis = $kn->nsis()->get();
        foreach ($nsis as $nsi)
        {
            array_push($arr,$nsi->id);
        }
        $kn->nsis = $arr;
        $kn->valid = true;
        //dd($sk->nsis);
        return $kn;
    }
}
