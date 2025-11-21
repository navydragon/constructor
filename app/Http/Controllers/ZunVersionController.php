<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Dpp;
use App\ZunVersion;
use App\IshVersion;
use App\Typology;
use App\DppTypologyPart;
use App\Skill;
use App\Ability;
use App\Knowledge;
use App\Competence;
use App\Question;
use App\Nsi;
use App\Ministry;
use App\StructureSection;
use App\StructureVersion;
use App\Http\Resources\KnowledgeImportResource;
class ZunVersionController extends Controller
{

    public function show(Dpp $dpp, $zv)
    {
        $result = [];
        $zuns = [];

        if ($dpp->zun_version_id == null) {
            $zv = $dpp->create_zun();
        }else{
            $zv = ZunVersion::find($zv);
        }
        $competences = Competence::where('zun_version_id',$zv->id)->get();
        $skills = Skill::where('zun_version_id',$zv->id)->orderBy('position','asc')->get();
        $abilities = Ability::where('zun_version_id',$zv->id)->orderBy('position','asc')->get();
        $knowledges = Knowledge::where('zun_version_id',$zv->id)->where('is_through',false)->orderBy('position','asc')->get();
        $th_knowledges = Knowledge::where('zun_version_id',$zv->id)->where('is_through',true)->orderBy('position','asc')->get();
        foreach ($competences as $competence)
        {
            $row = [];
            $row["id"] = 'c'.$competence->id;
            $row["name"] = $competence->name;
            $row["what"] = $competence->what;
            $row["with"] = $competence->with;
            $row["where"] = $competence->where;
            $row["pid"] = null;
            $row["type"] = "Компетенция";
            $row["valid"] = $competence->valid ? 0 : 1;
            $row["tags"][0] = 'competence';
            array_push($zuns,$row);
        }
        foreach ($skills as $skill)
        {
            $row = [];
            $row["id"] = 's'.$skill->id;
            $row["name"] = $skill->name;
            $row["what"] = $skill->what;
            $row["with"] = $skill->with;
            $row["where"] = $skill->where;
            $row["pid"] = 'c'.$skill->competence_id;
            $row["type"] = "Навык";
            $row["valid"] = $skill->valid ? 0 : 1;
            $row["tags"][0] = 'skill';
            $row["nsis"] = $skill->nsis()->pluck('id');
            $row["justificationType"] = $skill->is_by_expert;
            $row["expertOpinion"] = $skill->expert_answer;
            $row["position"] = $skill->position;
            $row["note"] = $skill->note;
            array_push($zuns,$row);
        }
        foreach ($abilities as $ability)
        {
            $row = [];
            $row["id"] = 'a'.$ability->id;
            $row["name"] = $ability->name;
            $row["what"] = $ability->what;
            $row["with"] = $ability->with;
            $row["where"] = $ability->where;
            if ($ability->has_parent_comp == true)
            {
                $row["pid"] = 'c'.$ability->competence_id;
            }else{
                $row["pid"] = 's'.$ability->skill_id;
            }
            $row["type"] = "Умение";
            $row["valid"] = $ability->valid ? 0 : 1;
            $row["tags"][0] = 'ability';
            $row["nsis"] = $ability->nsis()->pluck('id');
            $row["justificationType"] = $ability->is_by_expert;
            $row["expertOpinion"] = $ability->expert_answer;
            $row["position"] = $ability->position;
            $row["note"] = $ability->note;
            array_push($zuns,$row);
        }
        foreach ($knowledges as $knowledge)
        {
            $row = [];
            $row["id"] = 'k'.$knowledge->id;
            $row["name"] = $knowledge->name;
            $row["what"] = $knowledge->what;
            $row["pid"] = 'a'.$knowledge->ability_id;
            $row["type"] = "Знание";
            $row["valid"] = $knowledge->valid ? 0 : 1;
            $row["position"] = $knowledge->position;
            $row["tags"][0] = 'knowledge';
            $row["dtp"] = $knowledge->dtps()->get()->first();
            $row["nsis"] = $knowledge->nsis()->pluck('id');
            $row["justificationType"] = $knowledge->is_by_expert;
            $row["expertOpinion"] = $knowledge->expert_answer;
            $row["typologyPartId"] = $knowledge->dtps()->pluck('id')->first();
            $row["note"] = $knowledge->note;
            array_push($zuns,$row);
        }
        foreach ($th_knowledges as $knowledge)
        {
            $row = [];
            $row["id"] = 'k'.$knowledge->id;
            $row["name"] = $knowledge->name;
            $row["pid"] = 'th';
            $row["type"] = "Знание";
            $row["what"] = $knowledge->what;
            $row["valid"] = $knowledge->valid ? 0 : 1;
            $row["position"] = $knowledge->position;
            $row["tags"][0] = 'knowledge';
            $row["dtp"] = $knowledge->dtps()->get()->first();
            $row["nsis"] = $knowledge->nsis()->pluck('id');
            $row["justificationType"] = $knowledge->is_by_expert;
            $row["expertOpinion"] = $knowledge->expert_answer;
            $row["typologyPartId"] = $knowledge->dtps()->pluck('id')->first();
            $row["note"] = $knowledge->note;
            array_push($zuns,$row);
        }
        $row = [];
        $row["id"] = 'th';
        $row["name"] = ' ';
        $row["pid"] = null;
        $row["type"] = "Сквозные знания";
        $row["tags"][0] = 'through';
        $row["position"] = 99;
        array_push($zuns,$row);

        //links
        $additionalLinks = [];
        $knowledges = Knowledge::where('zun_version_id',$zv->id)->get();
        foreach ($knowledges as $knowledge)
        {
            $links = $knowledge->links;

            foreach ($links as $link)
            {
                $row = [];
                $row["from"] = 'k'.$knowledge->id;
                $row["to"] = 'a'.$link->id;
                $row["label"] = '';
                $row["template"] = 'blue';
                array_push($additionalLinks,$row);
            }
        }

        //typology
        $tp = [];
        $iv = IshVersion::find($dpp->ish_version_id);
        foreach($iv->typology_parts as $part)
        {
            $part->knowledges = $part->get_knowledges;
            array_push($tp,$part);
        }
        $result["zoons"] = $zuns;
        $result["links"] = $additionalLinks;
        $result["typologyParts"] = $tp;
        $result["ministries"] = Ministry::all();
        return json_encode($result);
    }

    public function children_order(ZunVersion $zv,$id, Request $request)
    {
        $type = substr($id, 0, 1);
        $id = substr($id,1);
        //$children = array_column($request->children,"id");
        $children = $request->children;
        switch ($type) {
            case 'a':
                $n = 1;
                foreach ($children as $child) {
                    $child_id = substr($child,1);
                    $kn = Knowledge::find($child_id);
                    $kn->position = $n;
                    $kn->save();
                    $n++;
                }
            break;
            case 's':
                $n = 1;
                foreach ($children as $child) {
                    $child_id = substr($child,1);
                    $ab = Ability::find($child_id);
                    $ab->position = $n;
                    $ab->save();
                    $n++;
                }
            break;
            case 't':
                $n = 1;
                foreach ($request->children as $child) {
                    $child_id = substr($child,1);
                    $kn = Knowledge::find($child_id);
                    $kn->position = $n;
                    $kn->save();
                    $n++;
                }
            break;
            case 'c':
                $n = 1;
                foreach ($children as $child) {
                    $child_id = substr($child,1);
                    if (substr($child, 0, 1) == 's')
                    {
                        $ab = Skill::find($child_id);
                        $ab->position = $n;
                        $ab->save();
                        $n++;
                    }
                    if (substr($child, 0, 1) == 'a')
                    {
                        $ab = Ability::find($child_id);
                        $ab->position = $n;
                        $ab->save();
                        $n++;
                    }
                }
            break;
            default:
                # code...
            break;
        }

        return response()->json(['message'=>'success'],200);
    }

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

    public function rebuild_knowledges(ZunVersion $zv, Request $request)
    {
        $dpp = Dpp::find($zv->dpp_id);
        $iv = IshVersion::find($dpp->ish_version_id);
        foreach($iv->typology_parts as $part)
        {
            $position = 1;
            //dd($part->knowledges);
            foreach ($part->knowledges as $knowledge)
            {
                $knowledge->dtps()->updateExistingPivot($part, array('position' => $position), false);
              //  $part->knowledges()->syncWithoutDetaching([$knowldge->id],['position' => $position]);
              $position++;
            }
        }
        return "";
    }

    public function reorder_upper_level(ZunVersion $zv, Request $request)
    {
        $ids = $request->elements;
        $position = 0;
        foreach ($ids as $item)
        {
            $position++;
            $type = substr($item, 0, 1);
            if ($type =='a')
            {
                $ability = Ability::find(substr($item,1));
                $ability->position = $position;
                $ability->save();
            }
            if ($type =='s')
            {
                $skill = Skill::find(substr($item,1));
                $skill->position = $position;
                $skill->save();
            }
            if ($type =='c')
            {
                $competence = Competence::find(substr($item,1));
                $competence->position = $position;
                $competence->save();
            }
        }
        return response(['message' => 'success',], 200);
    }

    public function dtp_knowledge_reorder(ZunVersion $zv, $dtp, Request $request)
    {
        $knowledges = $request->order;
        $position = 1;
        foreach ($knowledges as $id)
        {
            $knowledge = Knowledge::find($id);
            $knowledge->dtps()->updateExistingPivot($dtp, array('position' => $position), false);
            $theme = StructureSection::where('knowledge_id',$id)->first();
            if ($theme)
            {
                $theme->position = $position;
                $theme->save();
            }
            $position++;
        }
        return response(['message' => 'success',], 200);
    }

    // public function get_zun_version_data_unattached(Dpp $dpp,ZunVersion $zv)
    // {
    //     $result = [];
    //     $skills = Skill::where('zun_version_id',$zv->id)->where('competence_id',null)->get();
    //     foreach ($skills as $skill)
    //     {
    //         $skill_row = [];
    //         $skill_row["text"] = $skill->name;
    //         $skill_row["id"] = 's_'.$skill->id;
    //         $skill_row["color"] = "btn-secondary";
    //         $skill_row["icon"] = "ion ion-ios-radio-button-on text-secondary";
    //         $skill_row["type"] = "skil";
    //         $skill_row["opened"] = true;
    //         $skill_row["keyword"] = $skill->keyword;
    //         $skill_row["what"] = $skill->what;
    //         $skill_row["with"] = $skill->with;
    //         $skill_row["where"] = $skill->where;
    //         $skill_row["valid"] = true;
    //         $skill_row["is_main"] = true;
    //         $skill_row["children"] = [];
    //         $abilities = Ability::where('skill_id',$skill->id)->get();
    //         foreach ($abilities as $ability)
    //         {
    //             $ability_row = [];
    //             $ability_row["text"] = $ability->name;
    //             $ability_row["id"] = 'a_'.$ability->id;
    //             $ability_row["color"] = "btn-success";
    //             $ability_row["icon"] = "ion ion-ios-radio-button-on text-success";
    //             $ability_row["type"] = "abil";
    //             $ability_row["opened"] = true;
    //             $ability_row["keyword"] = $ability->keyword;
    //             $ability_row["what"] = $ability->what;
    //             $ability_row["with"] = $ability->with;
    //             $ability_row["where"] = $ability->where;
    //             $ability_row["valid"] = true;
    //             $ability_row["children"] = [];
    //             $knowledges = Knowledge::where('ability_id',$ability->id)->get();
    //             foreach ($knowledges as $knowledge)
    //             {
    //                 $knowledge_row = [];
    //                 $knowledge_row["text"] = $knowledge->name;
    //                 $knowledge_row["id"] = 'k_'.$knowledge->id;
    //                 $knowledge_row["color"] = "btn-warning";
    //                 $knowledge_row["icon"] = "ion ion-ios-radio-button-on text-warning";
    //                 $knowledge_row["type"] = "know";
    //                 $knowledge_row["opened"] = true;
    //                 $knowledge_row["keyword"] = $knowledge->keyword;
    //                 $knowledge_row["what"] = $knowledge->what;
    //                 $knowledge_row["with"] = $knowledge->with;
    //                 $knowledge_row["where"] = $knowledge->where;
    //                 $knowledge_row["valid"] = true;
    //                 $knowledge_row["children"] = [];
    //                 array_push($ability_row["children"],$knowledge_row);
    //             }
    //             array_push($skill_row["children"],$ability_row);
    //         }
    //         array_push($result,$skill_row);
    //     }
    //     $abilities = Ability::where('zun_version_id',$zv->id)
    //     ->where('skill_id',null)->where('competence_id',null)->get();
    //     foreach ($abilities as $ability)
    //         {
    //             $ability_row = [];
    //             $ability_row["text"] = $ability->name;
    //             $ability_row["id"] = 'a_'.$ability->id;
    //             $ability_row["color"] = "btn-success";
    //             $ability_row["icon"] = "ion ion-ios-radio-button-on text-success";
    //             $ability_row["type"] = "abil";
    //             $ability_row["opened"] = true;
    //             $ability_row["keyword"] = $ability->keyword;
    //             $ability_row["what"] = $ability->what;
    //             $ability_row["with"] = $ability->with;
    //             $ability_row["where"] = $ability->where;
    //             $ability_row["children"] = [];
    //             $ability_row["valid"] = true;
    //             $ability_row["is_main"] = true;
    //             $knowledges = Knowledge::where('ability_id',$ability->id)->get();
    //             foreach ($knowledges as $knowledge)
    //             {
    //                 $knowledge_row = [];
    //                 $knowledge_row["text"] = $knowledge->name;
    //                 $knowledge_row["id"] = 'k_'.$knowledge->id;
    //                 $knowledge_row["color"] = "btn-warning";
    //                 $knowledge_row["icon"] = "ion ion-ios-radio-button-on text-warning";
    //                 $knowledge_row["type"] = "know";
    //                 $knowledge_row["opened"] = true;
    //                 $knowledge_row["keyword"] = $knowledge->keyword;
    //                 $knowledge_row["what"] = $knowledge->what;
    //                 $knowledge_row["with"] = $knowledge->with;
    //                 $knowledge_row["where"] = $knowledge->where;
    //                 $knowledge_row["children"] = [];
    //                 $knowledge_row["valid"] = true;
    //                 array_push($ability_row["children"],$knowledge_row);
    //             }
    //             array_push($result,$ability_row);
    //         }
    //     return json_encode($result);
    // }

    // public function get_zun_version_data_attached(Dpp $dpp,ZunVersion $zv)
    // {
    //     $result = [];
    //     $competences = Competence::where('zun_version_id',$zv->id)->get();
    //     foreach ($competences as $competence)
    //     {
    //         $competence_row = [];
    //         $competence_row = [];
    //         $competence_row["text"] = $competence->name;
    //         $competence_row["id"] = 'c_'.$competence->id;
    //         $competence_row["color"] = "btn-primary";
    //         $competence_row["icon"] = "ion ion-ios-radio-button-on text-primary";
    //         $competence_row["type"] = "comp";
    //         $competence_row["opened"] = true;
    //         $competence_row["is_main"] = true;
    //         $competence_row["keyword"] = $competence->keyword;
    //         $competence_row["what"] = $competence->what;
    //         $competence_row["with"] = $competence->with;
    //         $competence_row["where"] = $competence->where;
    //         $competence_row["valid"] = true;
    //         $competence_row["children"] = [];
    //         $skills = Skill::where('zun_version_id',$zv->id)->where('competence_id',$competence->id)->get();
    //         foreach ($skills as $skill)
    //         {
    //             $skill_row = [];
    //             $skill_row["text"] = $skill->name;
    //             $skill_row["id"] = 's_'.$skill->id;
    //             $skill_row["color"] = "btn-secondary";
    //             $skill_row["icon"] = "ion ion-ios-radio-button-on text-secondary";
    //             $skill_row["type"] = "skil";
    //             $skill_row["opened"] = true;
    //             $skill_row["keyword"] = $skill->keyword;
    //             $skill_row["what"] = $skill->what;
    //             $skill_row["with"] = $skill->with;
    //             $skill_row["where"] = $skill->where;
    //             $skill_row["valid"] = true;
    //             $skill_row["children"] = [];
    //             $abilities = Ability::where('skill_id',$skill->id)->get();
    //             foreach ($abilities as $ability)
    //             {
    //                 $ability_row = [];
    //                 $ability_row["text"] = $ability->name;
    //                 $ability_row["id"] = 'a_'.$ability->id;
    //                 $ability_row["color"] = "btn-success";
    //                 $ability_row["icon"] = "ion ion-ios-radio-button-on text-success";
    //                 $ability_row["type"] = "abil";
    //                 $ability_row["opened"] = true;
    //                 $ability_row["keyword"] = $ability->keyword;
    //                 $ability_row["what"] = $ability->what;
    //                 $ability_row["with"] = $ability->with;
    //                 $ability_row["where"] = $ability->where;
    //                 $ability_row["valid"] = true;
    //                 $ability_row["children"] = [];
    //                 $knowledges = Knowledge::where('ability_id',$ability->id)->get();
    //                 foreach ($knowledges as $knowledge)
    //                 {
    //                     $knowledge_row = [];
    //                     $knowledge_row["text"] = $knowledge->name;
    //                     $knowledge_row["id"] = 'k_'.$knowledge->id;
    //                     $knowledge_row["color"] = "btn-warning";
    //                     $knowledge_row["icon"] = "ion ion-ios-radio-button-on text-warning";
    //                     $knowledge_row["type"] = "know";
    //                     $knowledge_row["opened"] = true;
    //                     $knowledge_row["keyword"] = $knowledge->keyword;
    //                     $knowledge_row["what"] = $knowledge->what;
    //                     $knowledge_row["with"] = $knowledge->with;
    //                     $knowledge_row["where"] = $knowledge->where;
    //                     $knowledge_row["valid"] = true;
    //                     $knowledge_row["children"] = [];
    //                     array_push($ability_row["children"],$knowledge_row);
    //                 }
    //                 array_push($skill_row["children"],$ability_row);
    //             }
    //             array_push($competence_row["children"],$skill_row);
    //         }

    //         $abilities = Ability::where('zun_version_id',$zv->id)
    //     ->where('skill_id',null)->where('competence_id',$competence->id)->get();
    //     foreach ($abilities as $ability)
    //         {
    //             $ability_row = [];
    //             $ability_row["text"] = $ability->name;
    //             $ability_row["id"] = 'a_'.$ability->id;
    //             $ability_row["color"] = "btn-success";
    //             $ability_row["icon"] = "ion ion-ios-radio-button-on text-success";
    //             $ability_row["type"] = "abil";
    //             $ability_row["opened"] = true;
    //             $ability_row["keyword"] = $ability->keyword;
    //             $ability_row["what"] = $ability->what;
    //             $ability_row["with"] = $ability->with;
    //             $ability_row["where"] = $ability->where;
    //             $ability_row["children"] = [];
    //             $ability_row["valid"] = true;
    //             $knowledges = Knowledge::where('ability_id',$ability->id)->get();
    //             foreach ($knowledges as $knowledge)
    //             {
    //                 $knowledge_row = [];
    //                 $knowledge_row["text"] = $knowledge->name;
    //                 $knowledge_row["id"] = 'k_'.$knowledge->id;
    //                 $knowledge_row["color"] = "btn-warning";
    //                 $knowledge_row["icon"] = "ion ion-ios-radio-button-on text-warning";
    //                 $knowledge_row["type"] = "know";
    //                 $knowledge_row["opened"] = true;
    //                 $knowledge_row["keyword"] = $knowledge->keyword;
    //                 $knowledge_row["what"] = $knowledge->what;
    //                 $knowledge_row["with"] = $knowledge->with;
    //                 $knowledge_row["where"] = $knowledge->where;
    //                 $knowledge_row["children"] = [];
    //                 $knowledge_row["valid"] = true;
    //                 array_push($ability_row["children"],$knowledge_row);
    //             }
    //             array_push($competence_row["children"],$ability_row);
    //         }
    //         array_push($result,$competence_row);
    //     }
    //     return json_encode($result);
    // }

    // public function delete_knowledge(Request $request)
    // {
    //     $id = $request->id;
    //     $id = explode("_",$id);
    //     $id = $id[1];
    //     Knowledge::destroy($id);
    // }

    // public function delete_ability(Request $request)
    // {
    //     $id = $request->id;
    //     $id = explode("_",$id);
    //     $id = $id[1];
    //     $kns = Knowledge::where('ability_id',$id)->get();
    //     foreach($kns as $kn)
    //     {
    //         Knowledge::destroy($kn->id);
    //     }
    //     Ability::destroy($id);
    // }

    // public function delete_skill(Request $request)
    // {
    //     $id = $request->id;
    //     $id = explode("_",$id);
    //     $id = $id[1];
    //     $abs = Ability::where('skill_id',$id)->get();
    //     foreach ($abs as $ab)
    //     {
    //         $kns = Knowledge::where('ability_id',$ab->id)->get();
    //         foreach($kns as $kn)
    //         {
    //             Knowledge::destroy($kn->id);
    //         }
    //         Ability::destroy($ab->id);
    //     }
    //     Skill::destroy($id);
    // }

    // public function delete_competence (Request $request)
    // {
    //     $id = $request->id;
    //     $id = explode("_",$id);
    //     $id = $id[1];

    //     $sks = Skill::where('competence_id',$id)->get();
    //     foreach ($sks as $sk)
    //     {
    //         $request->id = 's_'.$sk->id;
    //         $this->delete_skill($request);
    //     }

    //     $abs = Ability::where('competence_id',$id)->get();
    //     foreach ($abs as $ab)
    //     {
    //         $request->id = 'a_'.$ab->id;
    //         $this->delete_ability($request);
    //     }

    //     Competence::destroy($id);
    // }

    // public function update_elem(Dpp $dpp,ZunVersion $zv,Request $request)
    // {
    //     $item = $request->item;
    //     $id = $item["id"];
    //     $id2 = explode("_",$id);
    //     if ($id2[0]=='s')
    //     {
    //         $request->id = $id;
    //         $this->delete_skill($request);
    //         $request->skill = $request->item;
    //         $this->add_skill($dpp,$zv,$request);
    //     }
    //     if ($id2[0]=='a')
    //     {
    //         $request->id = $id;
    //         $this->delete_ability($request);
    //         $request->ability = $request->item;
    //         $this->add_ability($dpp,$zv,$request);
    //     }

    //     if ($id2[0]=='c')
    //     {
    //         $request->id = $id;
    //         $this->delete_competence($request);
    //         $request->competence = $request->item;
    //         $this->create_competence($dpp,$zv,$request);
    //     }
    // }

    /* version 2 */
    public function get_zun_version_data2(Dpp $dpp,ZunVersion $zv)
    {
        $result = [];
        $zuns =
        $competences = Competence::where('zun_version_id',$zv->id)->get();
        $skills = Skill::where('zun_version_id',$zv->id)->orderBy('position','asc')->get();
        $abilities = Ability::where('zun_version_id',$zv->id)->orderBy('position','asc')->get();
        $knowledges = Knowledge::where('zun_version_id',$zv->id)->where('is_through',false)->orderBy('position','asc')->get();
        $th_knowledges = Knowledge::where('zun_version_id',$zv->id)->where('is_through',true)->orderBy('position','asc')->get();
        foreach ($competences as $competence)
        {
            $row = [];
            $row["id"] = 'c'.$competence->id;
            $row["name"] = $competence->name;
            $row["pid"] = null;
            $row["type"] = "Компетенция";
            $row["valid"] = $competence->valid;
            $row["tags"][0] = 'competence';
            array_push($result,$row);
        }
        foreach ($skills as $skill)
        {
            $row = [];
            $row["id"] = 's'.$skill->id;
            $row["name"] = $skill->name;
            $row["what"] = $skill->what;
            $row["with"] = $skill->with;
            $row["where"] = $skill->where;
            $row["pid"] = 'c'.$skill->competence_id;
            $row["type"] = "Навык";
            $row["valid"] = $skill->valid;
            $row["tags"][0] = 'skill';
            array_push($result,$row);
        }
        foreach ($abilities as $ability)
        {
            $row = [];
            $row["id"] = 'a'.$ability->id;
            $row["name"] = $ability->name;
            $row["what"] = $ability->what;
            $row["with"] = $ability->with;
            $row["where"] = $ability->where;
            if ($ability->has_parent_comp == true)
            {
                $row["pid"] = 'c'.$ability->competence_id;
            }else{
                $row["pid"] = 's'.$ability->skill_id;
            }
            $row["type"] = "Умение";
            $row["valid"] = $ability->valid;
            $row["tags"][0] = 'ability';
            array_push($result,$row);
        }
        foreach ($knowledges as $knowledge)
        {
            $row = [];
            $row["id"] = 'k'.$knowledge->id;
            $row["name"] = $knowledge->name;
            $row["what"] = $knowledge->what;
            $row["pid"] = 'a'.$knowledge->ability_id;
            $row["type"] = "Знание";
            $row["valid"] = $knowledge->valid;
            $row["position"] = $knowledge->position;
            $row["tags"][0] = 'knowledge';
            array_push($result,$row);
        }
        foreach ($th_knowledges as $knowledge)
        {
            $row = [];
            $row["id"] = 'k'.$knowledge->id;
            $row["name"] = $knowledge->name;
            $row["pid"] = 'th';
            $row["type"] = "Знание";
            $row["what"] = $knowledge->what;
            $row["valid"] = $knowledge->valid;
            $row["tags"][0] = 'knowledge';
            array_push($result,$row);
        }
        $row = [];
        $row["id"] = 'th';
        $row["name"] = 'СКВОЗНЫЕ ЗНАНИЯ';
        $row["pid"] = null;
        $row["type"] = "Сквозные знания";
        $row["tags"][0] = 'through';
        array_push($result,$row);

        //links
        $additionalLinks = [];
        $knowledges = Knowledge::where('zun_version_id',$zv->id)->get();
        foreach ($knowledges as $knowledge)
        {
            $links = $knowledge->links;

            foreach ($links as $link)
            {
                $row = [];
                $row["from"] = 'k'.$knowledge->id;
                $row["to"] = 'a'.$link->id;
                $row["label"] = '';
                $row["template"] = 'blue';
                array_push($additionalLinks,$row);
            }
        }
        return json_encode($result);

    }


    public function get_zuns_to_om(Dpp $dpp,ZunVersion $zv)
    {
        $result = [];
        $competences = Competence::where('zun_version_id',$zv->id)->get();
        $skills = Skill::where('zun_version_id',$zv->id)->orderBy('position','asc')->get();
        $abilities = Ability::where('zun_version_id',$zv->id)->orderBy('position','asc')->get();
        foreach ($competences as $competence)
        {
            $row = [];
            $row["id"] = $competence->id;
            $row["name"] = $competence->name;
            $row["pid"] = null;
            $row["type"] = "Компетенция";
            array_push($result,$row);
        }
        foreach ($skills as $skill)
        {
            $row = [];
            $row["id"] = $skill->id;
            $row["name"] = $skill->name;
            $row["pid"] = $skill->competence_id;
            $row["type"] = "Навык";
            array_push($result,$row);
        }
        foreach ($abilities as $ability)
        {
            $row = [];
            $row["id"] = $ability->id;
            $row["name"] = $ability->name;
            if ($ability->has_parent_comp == true)
            {
                $row["pid"] = $ability->competence_id;
            }else{
                $row["pid"] = $ability->skill_id;
            }
            $row["type"] = "Умение";
            $row["has_parent_comp"] = $ability->has_parent_comp;
            array_push($result,$row);
        }
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
        $comp->valid = true;
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

    public function update_competence2  (Dpp $dpp, Request $request)
    {
        $data = $request->competence_data;
        $competence = Competence::find($data["id"]);

        $competence->name = $request->competence_name;
        $competence->keyword = $data["keyword"];
        $competence->what = $data["what"];
        $competence->with = $data["with"];
        $competence->where = $data["where"];
        $competence->save();

        $competence->new_id = 'c'.$competence->id;
        return $competence;
    }

    public function add_skill2(Dpp $dpp,ZunVersion $zv, Request $request)
    {
        $data = $request->skill_data;
        if ($request->parent_node != 'root')
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
        switch ($data["is_by_expert"]) {
            case '0':
                $skill->is_by_expert = 0;
                $skill->save();
                $skill->nsis()->sync($data["nsis"]);
                if (count($data["nsis"]) > 0)
                { $skill->valid = true; }else{ $skill->valid = false; }
                $skill->save();
            break;

            case '1':
                $skill->is_by_expert = 1;
                $skill->expert_answer = $data["expert_answer"];
                $skill->save();
                if (strlen($data["expert_answer"]) != 0)
                { $skill->valid = true; }else{ $skill->valid = false; }
                $skill->save();
            break;

            default:
                $skill->valid = false;
                $skill->save();
            break;
        }
        $skill->new_id = 's'.$skill->id;
        $skill->new_parent = $request->parent_node;
        return $skill;
    }

    public function add_skill_new(Dpp $dpp,ZunVersion $zv, Request $request)
    {
        $data = $request->node;
        if ($data["pid"] != '')
        { $parent_node = substr($data["pid"],1);
        }else { $parent_node = null;}
        $skill = new Skill;
        $skill->dpp_id = $dpp->id;
        $skill->zun_version_id = $zv->id;
        $skill->name = $data["name"];
        $skill->keyword = "Уметь";
        $skill->what = $data["what"];
        $skill->with = $data["with"];
        $skill->where = $data["where"];
        $skill->competence_id = $parent_node;
        $skill->save();
        // switch ($data["is_by_expert"]) {
        //     case '0':
        //         $skill->is_by_expert = 0;
        //         $skill->save();
        //         $skill->nsis()->sync($data["nsis"]);
        //         if (count($data["nsis"]) > 0)
        //         { $skill->valid = true; }else{ $skill->valid = false; }
        //         $skill->save();
        //     break;

        //     case '1':
        //         $skill->is_by_expert = 1;
        //         $skill->expert_answer = $data["expert_answer"];
        //         $skill->save();
        //         if (strlen($data["expert_answer"]) != 0)
        //         { $skill->valid = true; }else{ $skill->valid = false; }
        //         $skill->save();
        //     break;

        //     default:
        //         $skill->valid = false;
        //         $skill->save();
        //     break;
        // }
        $row = [];
        $row["id"] = 's'.$skill->id;
        $row["name"] = $skill->name;
        $row["pid"] = $data["pid"];
        $row["type"] = "Навык";
        $row["valid"] = $skill->valid;
        $row["what"] = $skill->what;
        $row["position"] = $skill->position;
        $row["tags"][0] = 'skill';
        return json_encode($row);
    }

    public function remove_skill2 (Request $request)
    {
        $id = substr($request->skill_id,1);
        $sk = Skill::find($id);
        $sk->nsis()->detach();
        Skill::destroy($id);
    }

    public function remove_skill_new(Dpp $dpp,ZunVersion $zv, Request $request)
    {
        $id = substr($request->nodeId,1);
        $sk = Skill::find($id);
        $sk->nsis()->detach();
        Skill::destroy($id);
        return json_encode("s".$id);
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
        switch ($data["is_by_expert"]) {
            case '0':
                $skill->is_by_expert = 0;
                $skill->expert_answer = null;
                $skill->save();
                $skill->nsis()->sync($data["nsis"]);
                if (count($data["nsis"]) > 0)
                { $skill->valid = true; }else{ $skill->valid = false; }
                $skill->save();
            break;

            case '1':
                $skill->is_by_expert = 1;
                $skill->expert_answer = $data["expert_answer"];
                if (strlen($data["expert_answer"]) != 0)
                { $skill->valid = true; }else{ $skill->valid = false; }
                $skill->save();
                $skill->nsis()->detach();
            break;

            default:
                $skill->valid = false;
                $skill->save();
            break;
        }
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
            $abs_c = Ability::where('skill_id','=',$parent_node)->get()->count();
            $ability->position = $abs_c + 1;
        }
        if ($request->parent_type == 'competence')
        {
            $ability->has_parent_comp = true;
            $ability->competence_id = $parent_node;
        }
        $ability->save();
        switch ($data["is_by_expert"]) {
            case '0':
                $ability->is_by_expert = 0;
                $ability->save();
                $ability->nsis()->sync($data["nsis"]);
                if (count($data["nsis"]) > 0)
                { $ability->valid = true; }else{ $ability->valid = false; }
                $ability->save();
            break;

            case '1':
                $ability->is_by_expert = 1;
                $ability->expert_answer = $data["expert_answer"];
                if (strlen($data["expert_answer"]) != 0)
                { $ability->valid = true; }else{ $ability->valid = false; }
                $ability->save();
            break;

            default:
                $ability->valid = false;
                $ability->save();
            break;
        }
        $ability->new_id = 'a'.$ability->id;
        $ability->new_parent = $request->parent_node;
        return $ability;
    }

    public function add_ability_new(Dpp $dpp,ZunVersion $zv, Request $request)
    {
        $data = $request->node;
        if ($data["pid"] != '')
        { $parent_node = substr($data["pid"],1);
        }else { $parent_node = null;}
        $ability = new Ability;
        $ability->dpp_id = $dpp->id;
        $ability->zun_version_id = $zv->id;
        $ability->name = $data["name"];
        $ability->keyword = 'Уметь';
        $ability->what = $data["what"];
        $ability->with = $data["with"];
        $ability->where = $data["where"];
        if ($parent_node == null)
        {
            $ability->has_parent_comp = false;
        }
        if (substr($data["pid"], 0, 1) == 's')
        {
            $ability->has_parent_comp = false;
            $ability->skill_id = $parent_node;
            $abs_c = Ability::where('skill_id','=',$parent_node)->get()->count();
            $ability->position = $abs_c + 1;
        }
        if (substr($data["pid"], 0, 1) == 'c')
        {
            $ability->has_parent_comp = true;
            $ability->competence_id = $parent_node;
        }
        $ability->save();
        // switch ($data["is_by_expert"]) {
        //     case '0':
        //         $ability->is_by_expert = 0;
        //         $ability->save();
        //         $ability->nsis()->sync($data["nsis"]);
        //         if (count($data["nsis"]) > 0)
        //         { $ability->valid = true; }else{ $ability->valid = false; }
        //         $ability->save();
        //     break;

        //     case '1':
        //         $ability->is_by_expert = 1;
        //         $ability->expert_answer = $data["expert_answer"];
        //         if (strlen($data["expert_answer"]) != 0)
        //         { $ability->valid = true; }else{ $ability->valid = false; }
        //         $ability->save();
        //     break;

        //     default:
        //         $ability->valid = false;
        //         $ability->save();
        //     break;
        // }
        $row = [];
        $row["id"] = 'a'.$ability->id;
        $row["name"] = $ability->name;
        $row["pid"] = $data["pid"];
        $row["type"] = "Умение";
        $row["valid"] = $ability->valid;
        $row["what"] = $ability->what;
        $row["position"] = $ability->position;
        $row["tags"][0] = 'ability';
        return json_encode($row);
    }

    public function remove_ability2 (Request $request)
    {
        $id = substr($request->ability_id,1);
        $ab = Ability::find($id);
        $ab->nsis()->detach();
        Ability::destroy($id);
    }

    public function remove_ability_new(Dpp $dpp,ZunVersion $zv, Request $request)
    {
        $id = substr($request->nodeId,1);
        $ab = Ability::find($id);
        $ab->nsis()->detach();
        Ability::destroy($id);
        return json_encode("a".$id);
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
        switch ($data["is_by_expert"]) {
            case '0':
                $ability->is_by_expert = 0;
                $ability->expert_answer = null;
                $ability->save();
                $ability->nsis()->sync($data["nsis"]);
                if (count($data["nsis"]) > 0)
                { $ability->valid = true; }else{ $ability->valid = false; }
                $ability->save();
            break;

            case '1':
                $ability->is_by_expert = 1;
                $ability->expert_answer = $data["expert_answer"];
                $ability->save();
                $ability->nsis()->detach();
                if (strlen($data["expert_answer"]) != 0)
                { $ability->valid = true; }else{ $ability->valid = false; }
                $ability->save();
            break;

            default:
                $ability->valid = false;
                $ability->save();
            break;
        }
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
        if ($request->parent_node == 'th')
        {
            $knowledge->is_through = true;
            $knowledge->ability_id = null;
        }else{
            $knowledge->is_through = false;
            $parent_node = substr($request->parent_node,1);
            $knowledge->ability_id = $parent_node;
        }

        $knowledge->save();
        if ($data["dtp"] != ""){
            $knowledge->get_dtps()->attach($data["dtp"]);
        }
        switch ($data["is_by_expert"]) {
            case '0':
                $knowledge->is_by_expert = 0;
                $knowledge->save();
                $knowledge->nsis()->sync($data["nsis"]);
                if (count($data["nsis"]) > 0 && $data["dtp"] != "")
                { $knowledge->valid = true; }else{ $knowledge->valid = false; }
                $knowledge->save();
            break;

            case '1':
                $knowledge->is_by_expert = 1;
                $knowledge->expert_answer = $data["expert_answer"];
                $knowledge->save();
                if (strlen($data["expert_answer"]) != 0 && $data["dtp"] != "")
                { $knowledge->valid = true; }else{ $knowledge->valid = false; }
                $knowledge->save();
            break;

            default:
                # code...
                break;
        }
        $knowledge->new_id = 'k'.$knowledge->id;
        $knowledge->new_parent = $request->parent_node;
        return $knowledge;
    }

    public function add_knowledge_new(Dpp $dpp,ZunVersion $zv, Request $request)
    {
        $data = $request->node;
        $knowledge = new Knowledge;
        $knowledge->dpp_id = $dpp->id;
        $knowledge->zun_version_id = $zv->id;
        $knowledge->name = $data["name"];
        $knowledge->keyword = 'Знать';
        $knowledge->what = $data["what"];
        $knowledge->with = " ";
        $knowledge->where = " ";
        if ($data["pid"] == 'th')
        {
            $knowledge->is_through = true;
            $knowledge->ability_id = null;
        }else{
            $knowledge->is_through = false;
            $parent_node = substr($data["pid"],1);
            $knowledge->ability_id = $parent_node;
        }

        $knowledge->save();
        // if ($data["dtp"] != ""){
        //     $knowledge->get_dtps()->attach($data["dtp"]);
        // }
        // switch ($data["is_by_expert"]) {
        //     case '0':
        //         $knowledge->is_by_expert = 0;
        //         $knowledge->save();
        //         $knowledge->nsis()->sync($data["nsis"]);
        //         if (count($data["nsis"]) > 0 && $data["dtp"] != "")
        //         { $knowledge->valid = true; }else{ $knowledge->valid = false; }
        //         $knowledge->save();
        //     break;

        //     case '1':
        //         $knowledge->is_by_expert = 1;
        //         $knowledge->expert_answer = $data["expert_answer"];
        //         $knowledge->save();
        //         if (strlen($data["expert_answer"]) != 0 && $data["dtp"] != "")
        //         { $knowledge->valid = true; }else{ $knowledge->valid = false; }
        //         $knowledge->save();
        //     break;

        //     default:
        //         # code...
        //         break;
        // }
            $row = [];
            $row["id"] = 'k'.$knowledge->id;
            $row["name"] = $knowledge->name;
            $row["pid"] = $data["pid"];
            $row["type"] = "Знание";
            $row["valid"] = $knowledge->valid;
            $row["what"] = $knowledge->what;
            $row["position"] = $knowledge->position;
            $row["tags"][0] = 'knowledge';
        return json_encode($row);
    }

    public function remove_knowledge2 (Request $request)
    {
        $id = substr($request->knowledge_id,1);
        $kn = Knowledge::find($id);
        $kn->nsis()->detach();
        $kn->links()->detach();
        $kn->get_dtps()->detach();
        foreach($kn->questions as $question)
        {
            $q = Question::findOrFail($question->id);
            $q->knowledge_id = null;
            $q->save();
        }
        Knowledge::destroy($id);
    }

    public function remove_knowledge_new(Dpp $dpp,ZunVersion $zv, Request $request)
    {
        //$data = $request->node;
        $id = substr($request->nodeId,1);
        $kn = Knowledge::find($id);
        $kn->nsis()->detach();
        $kn->links()->detach();
        $kn->get_dtps()->detach();
        foreach($kn->questions as $question)
        {
            $q = Question::findOrFail($question->id);
            $q->knowledge_id = null;
            $q->save();
        }
        Knowledge::destroy($id);
        return json_encode("k".$id);
    }

    public function update_knowledge2 (Dpp $dpp, Request $request)
    {
        $data = $request->knowledge_data;
        $knowledge = Knowledge::find($data["id"]);
        $knowledge->name = $request->knowledge_name;
        $knowledge->what = $data["what"];
        $knowledge->save();
        if ($data["dtp"] != ""){
            $knowledge->get_dtps()->sync($data["dtp"]);
        }
        switch ($data["is_by_expert"]) {
            case '0':
                $knowledge->is_by_expert = 0;
                $knowledge->expert_answer = null;
                $knowledge->save();
                $knowledge->nsis()->sync($data["nsis"]);
                if (count($data["nsis"]) > 0 && $data["dtp"] != "")
                { $knowledge->valid = true; }else{ $knowledge->valid = false; }
                $knowledge->save();
            break;

            case '1':
                $knowledge->is_by_expert = 1;
                $knowledge->expert_answer = $data["expert_answer"];
                $knowledge->save();
                $knowledge->nsis()->detach();
                if (strlen($data["expert_answer"]) != 0 && $data["dtp"] != "")
                { $knowledge->valid = true; }else{ $knowledge->valid = false; }
                $knowledge->save();
            break;

            default:
                # code...
                break;
        }
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

    public function remove_knowledge_link2(Dpp $dpp, Request $request)
    {
        $knowledge_id = substr($request->knowledge_id,1);
        $ability_id = substr($request->ability_id,1);
        $kn = Knowledge::find($knowledge_id);
        $kn->links()->detach($ability_id);
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
                $kn->moveUpBelowKnowledges();
                $kn->setPositionAndParent($request->to_id);
                // if ($to_type == 'Сквозные знания')
                // {
                //     $kn->ability_id = null;
                //     $kn->is_through = true;
                // }

                // if ($to_type == 'Умение')
                // {
                //     $kn->is_through = false;
                //     $kn->ability_id = $to_id;
                // }
                $kn->save();
                $sv = $kn->dpp->st_version_id;
                if ($sv != null)
                {
                    $sv = StructureVersion::find($sv);
                    $sv->reattach_practice();
                }
            break;

            case 'Навык':
                $sk = Skill::find($elem_id);
                $sk->moveUpBelow();
                $sk->setPositionAndParent($request->to_id);
                //$sk->competence_id = $to_id;
                $sk->save();
            break;

            case 'Умение':
                $ab = Ability::find($elem_id);
                $ab->moveUpBelow();
                $ab->setPositionAndParent($request->to_id);
                // if ($to_type == 'Компетенция')
                // {
                //     $ab->has_parent_comp = true;
                //     $ab->skill_id = null;
                //     $ab->competence_id = $to_id;
                // }
                // if ($to_type == 'Навык')
                // {
                //     $ab->has_parent_comp = false;
                //     $ab->skill_id = $to_id;
                //     $ab->competence_id = null;
                // }
                $ab->save();
                $sv = $ab->dpp->st_version_id;
                if ($sv != null)
                {
                    $sv = StructureVersion::find($sv);
                    $sv->reattach_practice();
                }
            break;

            default:
            break;
        }
        return response(['message' => 'success',], 200);
    }

    public function disconnect_node(Request $request)
    {
        $el = $request->node;
        $el["id"] = substr($el["id"],1);
        switch ($el["type"]) {
            case 'Знание':
                $kn = Knowledge::find($el["id"]);
                $kn->moveUpBelowKnowledges();
                $kn->ability_id = null;
                $kn->is_through = false;
                $kn->save();
                $sv = $kn->dpp->st_version_id;
                if ($sv != null)
                {
                    $sv = StructureVersion::find($sv);
                    $sv->reattach_practice();
                }
            break;
            case 'Умение':
                $ab = Ability::find($el["id"]);
                $ab->moveUpBelow();
                if ($ab->has_parent_comp == true)
                {
                    $ab->competence_id = null;
                }else{
                    $ab->skill_id = null;
                }
                $ab->save();
                $sv = $ab->dpp->st_version_id;
                if ($sv != null)
                {
                    $sv = StructureVersion::find($sv);
                    $sv->reattach_practice();
                }
            break;

            case 'Навык':
                $sk = Skill::find($el["id"]);
                $sk->moveUpBelow();
                $sk->competence_id = null;
                $sk->save();
            break;

            default:
                # code...
                break;
        }
        return response()->json(['message'=>'success'],200);
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
        $knowledges = Knowledge::where('zun_version_id',$zv->id)->get();
        foreach ($knowledges as $knowledge)
        {
            $links = $knowledge->links;

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
        foreach($iv->typology_parts as $part)
        {
            $part->knowledges = $part->get_knowledges;
        }
        return json_encode($iv->typology_parts);
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
        if ($kn->get_dtps()->count() != 0)
        {
        $kn->dtp = $kn->get_dtps()->first()->id;
        }else{
        $kn->dtp = "";
        }
        //dd($sk->nsis);
        return $kn;
    }

    public function get_competence_info ($co)
    {
        $id = substr($co,1);
        $co = Competence::find($id);
        return $co;
    }

    public function set_zun_valid()
    {
        $comps = Competence::all();
        foreach ($comps as $comp)
        {
            $comp->valid = true;
            $comp->save();
        }
    }

    public function make_positions()
    {
        $dpps = Dpps::all();
    }

    public function get_children(ZunVersion $zv,Request $request)
    {
        $type = $request->elem_type;
        $id = substr($request->elem_id,1);
        switch ($type) {
            case 'ability':
                $elem = Ability::find($id);
                return $elem->knowledges;
            break;
            case 'skill':
                $elem = Skill::find($id);
                return $elem->abilities;
            break;
            case 'competence':
                $elem = Competence::find($id);
                if ($elem->skills->count() == 0) { return $elem->abilities;}
                return $elem->skills;
            break;
            case 'through':
                $th_knowledges = Knowledge::where('zun_version_id',$zv->id)->where('is_through',true)->orderBy('position','asc')->get();
                return $th_knowledges;
            break;

            default:
                # code...
                break;
        }
    }

    public function import_competences(Dpp $dpp, Dpp $old_dpp)
    {
        $zv = $dpp->zun_version;
        $old_zv = $old_dpp->zun_version;
        $competences = $old_zv->competences;
        foreach ($competences as $competence)
        {
            $new_competence = $competence->replicate();
            $new_competence->dpp_id = $zv->dpp_id;
            $new_competence->zun_version_id = $zv->id;
            $new_competence->push();

            foreach ($competence->skills as $skill)
            {
                $new_skill = $skill->replicate();
                $new_skill->dpp_id = $zv->dpp_id;
                $new_skill->zun_version_id = $zv->id;
                $new_skill->competence_id = $new_competence->id;
                $new_skill->push();
                foreach ($skill->nsis as $nsi)
                {
                    //проверка на наличие
                    $exist_nsi = $zv->dpp->ish_version->nsis()->where('nsiFullName',$nsi->nsiFullName)->get()->first();
                    if ($exist_nsi != null)
                    {
                        $new_skill->nsis()->attach($exist_nsi->id);
                    }else{
                        $new_nsi = $nsi->replicate();
                        $new_nsi->ish_version_id = $zv->dpp->ish_version->id;
                        $new_nsi->push();
                        $new_skill->nsis()->attach($new_nsi->id);
                    }
                }
                foreach($skill->abilities as $ability)
                {
                    $new_ability = $ability->replicate();
                    $new_ability->dpp_id = $zv->dpp_id;
                    $new_ability->zun_version_id = $zv->id;
                    $new_ability->skill_id = $new_skill->id;
                    $new_ability->push();

                    foreach ($ability->nsis as $nsi)
                    {
                        //проверка на наличие
                        $exist_nsi = $zv->dpp->ish_version->nsis()->where('nsiFullName',$nsi->nsiFullName)->get()->first();
                        if ($exist_nsi != null)
                        {
                            $new_ability->nsis()->attach($exist_nsi->id);
                        }else{
                            $new_nsi = $nsi->replicate();
                            $new_nsi->ish_version_id = $zv->dpp->ish_version->id;
                            $new_nsi->push();
                            $new_ability->nsis()->attach($new_nsi->id);
                        }
                    }

                    foreach ($ability->knowledges as $knowledge)
                    {
                        $new_knowledge = $knowledge->replicate();
                        $new_knowledge->dpp_id = $zv->dpp_id;
                        $new_knowledge->zun_version_id = $zv->id;
                        $new_knowledge->ability_id = $new_ability->id;
                        $new_knowledge->push();

                        foreach ($knowledge->nsis as $nsi)
                        {
                            //проверка на наличие
                            $exist_nsi = $zv->dpp->ish_version->nsis()->where('nsiFullName',$nsi->nsiFullName)->get()->first();
                            if ($exist_nsi != null)
                            {
                                $new_knowledge->nsis()->attach($exist_nsi->id);
                            }else{
                                $new_nsi = $nsi->replicate();
                                $new_nsi->ish_version_id = $zv->dpp->ish_version->id;
                                $new_nsi->push();
                                $new_knowledge->nsis()->attach($new_nsi->id);
                            }
                        }

                        foreach ($knowledge->questions as $question)
                        {
                            $new_question = $question->replicate();
                            $new_question->om_version_id = $zv->dpp->om_version->id;
                            $new_question->knowledge_id = $new_knowledge->id;
                            $new_question->push();
                            foreach($question->single_choice_answers as $answer)
                            {
                                $new_answer = $answer->replicate();
                                $new_answer->question_id = $new_question->id;
                                $new_answer->push();
                            }
                            foreach($question->multi_choice_answers as $answer)
                            {
                                $new_answer = $answer->replicate();
                                $new_answer->question_id = $new_question->id;
                                $new_answer->push();
                            }
                            foreach($question->free_choice_answers as $answer)
                            {
                                $new_answer = $answer->replicate();
                                $new_answer->question_id = $new_question->id;
                                $new_answer->push();
                            }
                            foreach($question->sequence_choice_answers as $answer)
                            {
                                $new_answer = $answer->replicate();
                                $new_answer->question_id = $new_question->id;
                                $new_answer->push();
                            }
                            foreach($question->accordance_choice_answers as $answer)
                            {
                                $new_answer = $answer->replicate();
                                $new_answer->question_id = $new_question->id;
                                $new_answer->push();
                            }
                        }

                        $lection = $knowledge->lection;
                        if ($lection != null && $lection->is_loaded == true)
                        {
                            $new_knowledge->hidden_lection_id = $lection->id;
                            $new_knowledge->save();
                        }
                    }
                }
            }

            foreach ($competence->abilities as $ability)
            {
                $new_ability = $ability->replicate();
                $new_ability->dpp_id = $zv->dpp_id;
                $new_ability->zun_version_id = $zv->id;
                $new_ability->competence_id = $new_competence->id;
                $new_ability->push();

                foreach ($ability->nsis as $nsi)
                {
                    //проверка на наличие
                    $exist_nsi = $zv->dpp->ish_version->nsis()->where('nsiFullName',$nsi->nsiFullName)->get()->first();
                    if ($exist_nsi != null)
                    {
                        $new_ability->nsis()->attach($exist_nsi->id);
                    }else{
                        $new_nsi = $nsi->replicate();
                        $new_nsi->ish_version_id = $zv->dpp->ish_version->id;
                        $new_nsi->push();
                        $new_ability->nsis()->attach($new_nsi->id);
                    }
                }

                foreach ($ability->knowledges as $knowledge)
                {
                    $new_knowledge = $knowledge->replicate();
                    $new_knowledge->dpp_id = $zv->dpp_id;
                    $new_knowledge->zun_version_id = $zv->id;
                    $new_knowledge->ability_id = $new_ability->id;
                    $new_knowledge->push();

                    foreach ($knowledge->nsis as $nsi)
                    {
                        //проверка на наличие
                        $exist_nsi = $zv->dpp->ish_version->nsis()->where('nsiFullName',$nsi->nsiFullName)->get()->first();
                        if ($exist_nsi != null)
                        {
                            $new_knowledge->nsis()->attach($exist_nsi->id);
                        }else{
                            $new_nsi = $nsi->replicate();
                            $new_nsi->ish_version_id = $zv->dpp->ish_version->id;
                            $new_nsi->push();
                            $new_knowledge->nsis()->attach($new_nsi->id);
                        }
                    }

                    foreach ($knowledge->questions as $question)
                    {
                        $new_question = $question->replicate();
                        $new_question->om_version_id = $zv->dpp->om_version->id;
                        $new_question->knowledge_id = $new_knowledge->id;
                        $new_question->push();
                        foreach($question->single_choice_answers as $answer)
                        {
                            $new_answer = $answer->replicate();
                            $new_answer->question_id = $new_question->id;
                            $new_answer->push();
                        }
                        foreach($question->multi_choice_answers as $answer)
                        {
                            $new_answer = $answer->replicate();
                            $new_answer->question_id = $new_question->id;
                            $new_answer->push();
                        }
                        foreach($question->free_choice_answers as $answer)
                        {
                            $new_answer = $answer->replicate();
                            $new_answer->question_id = $new_question->id;
                            $new_answer->push();
                        }
                        foreach($question->sequence_choice_answers as $answer)
                        {
                            $new_answer = $answer->replicate();
                            $new_answer->question_id = $new_question->id;
                            $new_answer->push();
                        }
                        foreach($question->accordance_choice_answers as $answer)
                        {
                            $new_answer = $answer->replicate();
                            $new_answer->question_id = $new_question->id;
                            $new_answer->push();
                        }
                    }

                    $lection = $knowledge->lection;
                    if ($lection != null && $lection->is_loaded == true)
                    {
                        $new_knowledge->hidden_lection_id = $lection->id;
                        $new_knowledge->save();
                    }
                }
            }
        }

        $th_knowledges = $old_zv->knowledges->where('is_through',1);

        foreach ($th_knowledges as $knowledge)
        {
            $new_knowledge = $knowledge->replicate();
            $new_knowledge->dpp_id = $zv->dpp_id;
            $new_knowledge->zun_version_id = $zv->id;
            $new_knowledge->push();

            foreach ($knowledge->nsis as $nsi)
            {
                //проверка на наличие
                $exist_nsi = $zv->dpp->ish_version->nsis()->where('nsiFullName',$nsi->nsiFullName)->get()->first();
                if ($exist_nsi != null)
                {
                    $new_knowledge->nsis()->attach($exist_nsi->id);
                }else{
                    $new_nsi = $nsi->replicate();
                    $new_nsi->ish_version_id = $zv->dpp->ish_version->id;
                    $new_nsi->push();
                    $new_knowledge->nsis()->attach($new_nsi->id);
                }
            }

            foreach ($knowledge->questions as $question)
            {
                $new_question = $question->replicate();
                $new_question->om_version_id = $zv->dpp->om_version->id;
                $new_question->knowledge_id = $new_knowledge->id;
                $new_question->push();
                foreach($question->single_choice_answers as $answer)
                {
                    $new_answer = $answer->replicate();
                    $new_answer->question_id = $new_question->id;
                    $new_answer->push();
                }
                foreach($question->multi_choice_answers as $answer)
                {
                    $new_answer = $answer->replicate();
                    $new_answer->question_id = $new_question->id;
                    $new_answer->push();
                }
                foreach($question->free_choice_answers as $answer)
                {
                    $new_answer = $answer->replicate();
                    $new_answer->question_id = $new_question->id;
                    $new_answer->push();
                }
                foreach($question->sequence_choice_answers as $answer)
                {
                    $new_answer = $answer->replicate();
                    $new_answer->question_id = $new_question->id;
                    $new_answer->push();
                }
                foreach($question->accordance_choice_answers as $answer)
                {
                    $new_answer = $answer->replicate();
                    $new_answer->question_id = $new_question->id;
                    $new_answer->push();
                }
            }

            $lection = $knowledge->lection;
            if ($lection != null && $lection->is_loaded == true)
            {
                $new_knowledge->hidden_lection_id = $lection->id;
                $new_knowledge->save();
            }
        }

    }

    public function apply_structure(Dpp $dpp, Dpp $old_dpp)
    {
        // $iv = IshVersion::find($dpp->ish_version_id);
        $old_parts = $old_dpp->ish_version->typology_parts;
        foreach($old_parts as $old_part)
        {
            $new_part = DppTypologyPart::where('name',$old_part->name)->where('dpp_id',$dpp->id)->get()->first();
            
            // Если типологическая часть не найдена, создаем ее
            if (!$new_part) {
                $new_part = $old_part->replicate();
                $new_part->dpp_id = $dpp->id;
                $new_part->ish_version_id = $dpp->ish_version_id;
                $new_part->push();
            }
            
            foreach ($old_part->knowledges as $old_knowledge)
            {
                $new_knowledge = Knowledge::where('name',$old_knowledge->name)->where('zun_version_id',$dpp->zun_version->id)->get()->first();
                if ($new_knowledge) {
                    $new_part->knowledges()->syncWithoutDetaching($new_knowledge->id,['position' => $old_knowledge->pivot->position]);
                    $sv = $new_knowledge->dpp->st_version_id;
                    if ($sv != null  && $new_knowledge->dtps->count() != 0)
                    {
                        $new_theme = StructureSection::where('knowledge_id',$new_knowledge->id)->get()->first();
                        if ($new_theme)
                        {
                            $new_knowledge->update_theme($sv);
                        }else{
                            $new_knowledge->add_theme($sv);
                        }
                        $old_theme = StructureSection::where('knowledge_id',$old_knowledge->id)->get()->first();
                        $new_theme = StructureSection::where('knowledge_id',$new_knowledge->id)->get()->first();

                        if ($old_theme && $new_theme) {
                            $new_theme->lection_hours = $old_theme->lection_hours;
                            $new_theme->practice_hours = $old_theme->practice_hours;
                            $new_theme->self_hours = $old_theme->self_hours;
                            $new_theme->total_hours = $old_theme->total_hours;
                            $new_theme->lab_hours = $old_theme->lab_hours;
                            $new_theme->attestation_hours = $old_theme->attestation_hours;
                            $new_theme->save();
                        }
                    }
                }
            }
        }
    }

    public function import_knowledge(ZunVersion $zv, Request $request)
    {
        $old_knowledge = Knowledge::findOrFail($request->knowledge_id);
        $new_knowledge = new Knowledge;
        $new_knowledge->dpp_id = $zv->dpp_id;
        $new_knowledge->zun_version_id = $zv->id;
        $new_knowledge->name = $old_knowledge->name;
        $new_knowledge->is_through = $old_knowledge->is_through;
        $new_knowledge->keyword = $old_knowledge->keyword;
        $new_knowledge->what = $old_knowledge->what;
        $new_knowledge->with = $old_knowledge->with;
        $new_knowledge->where = $old_knowledge->where;
        $new_knowledge->valid = false;
        $new_knowledge->position = $zv->get_new_position() + 1;
        $new_knowledge->note = $old_knowledge->note;

        $new_knowledge->is_by_expert = $old_knowledge->is_by_expert;
        $new_knowledge->expert_answer = $old_knowledge->expert_answer;
        $new_knowledge->save();
        foreach ($old_knowledge->nsis as $nsi)
        {
            //проверка на наличие
            $exist_nsi = $zv->dpp->ish_version->nsis()->where('nsiFullName',$nsi->nsiFullName)->get()->first();
            if ($exist_nsi != null)
            {
                $new_knowledge->nsis()->attach($exist_nsi->id);
            }else{
                $new_nsi = $nsi->replicate();
                $new_nsi->ish_version_id = $zv->dpp->ish_version->id;
                $new_nsi->push();
                $new_knowledge->nsis()->attach($new_nsi->id);
            }
        }

        foreach ($old_knowledge->questions as $question)
        {
            $new_question = $question->replicate();
            $new_question->om_version_id = $zv->dpp->om_version->id;
            $new_question->knowledge_id = $new_knowledge->id;
            $new_question->push();
            foreach($question->single_choice_answers as $answer)
            {
                $new_answer = $answer->replicate();
                $new_answer->question_id = $new_question->id;
                $new_answer->push();
            }
            foreach($question->multi_choice_answers as $answer)
            {
                $new_answer = $answer->replicate();
                $new_answer->question_id = $new_question->id;
                $new_answer->push();
            }
            foreach($question->free_choice_answers as $answer)
            {
                $new_answer = $answer->replicate();
                $new_answer->question_id = $new_question->id;
                $new_answer->push();
            }
            foreach($question->sequence_choice_answers as $answer)
            {
                $new_answer = $answer->replicate();
                $new_answer->question_id = $new_question->id;
                $new_answer->push();
            }
            foreach($question->accordance_choice_answers as $answer)
            {
                $new_answer = $answer->replicate();
                $new_answer->question_id = $new_question->id;
                $new_answer->push();
            }
        }

        $lection = $old_knowledge->lection;
        if ($lection != null && $lection->is_loaded == true)
        {
            $new_knowledge->hidden_lection_id = $lection->id;
            $new_knowledge->save();
        }

        return new KnowledgeImportResource($new_knowledge);
    }

    public function change_first_letter()
    {
        // $kns = Knowledge::all();
        // foreach ($kns as $kn)
        // {
        //     $arr = explode(" ",$kn->what);

        //     if (substr($arr[0], 0, 1) == ',')
        //     {
        //         array_shift($arr);
        //     }
        //     $work = $arr[0];
        //     $capitalsAmount = mb_strlen( preg_replace('/[^A-ZА-ЯЁ]/u', '', $work), 'UTF-8');
        //     if ($capitalsAmount == 1)
        //     {
        //         $arr[0] = mb_strtolower($arr[0]);
        //         $kn->what = implode(" ",$arr);
        //         $kn->name = "Знать ".$kn->what;
        //         $kn->save();
        //     }
        // }

        $abs = Ability::all();
        foreach ($abs as $ab)
        {
            $arr = explode(" ",$ab->what);

            if (substr($arr[0], 0, 1) == ',')
            {
                array_shift($arr);
            }
            $work = $arr[0];
            $capitalsAmount = mb_strlen( preg_replace('/[^A-ZА-ЯЁ]/u', '', $work), 'UTF-8');
            if ($capitalsAmount == 1)
            {
                $arr[0] = mb_strtolower($arr[0]);
                $ab->what = implode(" ",$arr);
                $ab->name = $ab->keyword." ".$ab->what." ".$ab->with." ".$ab->where;
                $ab->save();
            }
        }

        $sks = Skill::all();
        foreach ($sks as $sk)
        {
            $arr = explode(" ",$sk->what);

            if (substr($arr[0], 0, 1) == ',')
            {
                array_shift($arr);
            }
            $work = $arr[0];
            $capitalsAmount = mb_strlen( preg_replace('/[^A-ZА-ЯЁ]/u', '', $work), 'UTF-8');
            if ($capitalsAmount == 1)
            {
                $arr[0] = mb_strtolower($arr[0]);
                $sk->what = implode(" ",$arr);
                $sk->name = $sk->keyword." ".$sk->what." ".$sk->with." ".$sk->where;
                $sk->save();
            }
        }

        $cts = Competence::all();
        foreach ($cts as $ct)
        {
            $arr = explode(" ",$ct->what);

            if (substr($arr[0], 0, 1) == ',')
            {
                array_shift($arr);
            }
            $work = $arr[0];
            $capitalsAmount = mb_strlen( preg_replace('/[^A-ZА-ЯЁ]/u', '', $work), 'UTF-8');
            if ($capitalsAmount == 1)
            {
                $arr[0] = mb_strtolower($arr[0]);
                $ct->what = implode(" ",$arr);
                $ct->name = $ct->keyword." ".$ct->what." ".$ct->with." ".$ct->where;
                $ct->save();
            }
        }
    }



}
