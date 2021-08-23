<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Knowledge;
use App\ZunVersion;

class KnowledgeController extends Controller
{
    public function show($kn)
    {
        if (is_numeric($kn[0])) {$id = $kn;}else{$id = substr($kn,1);}
        $kn = Knowledge::find($id);
        $kn->nsis = array_column($kn->nsis()->get()->toArray(),'id');
        $kn->valid = true;
        $dtps = $kn->get_dtps;
        
        if (count($dtps) != 0)
        {
            $kn->dtp = $dtps[0]->id;
        }else{ $kn->dtp = ""; }
        
        return $kn;
    }

    public function store(ZunVersion $zv, Request $request)
    {
        $data = $request->node; 
        $knowledge = new Knowledge;
        $knowledge->dpp_id = $zv->dpp_id;
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
         if ($data["typologyPartId"] != ""){
             $knowledge->get_dtps()->attach($data["typologyPartId"]);
         }
         switch ($data["justificationType"]) {
             case '0':
                 $knowledge->is_by_expert = 0;
                 $knowledge->save();
                 $knowledge->nsis()->sync($data["nsis"]);
                 if (count($data["nsis"]) > 0 && $data["typologyPartId"] != "" )
                 { $knowledge->valid = true; }else{ $knowledge->valid = false; }
                 $knowledge->save();
             break;
            case '1':
                $knowledge->is_by_expert = 1;
                $knowledge->expert_answer = $data["expertOpinion"];
                $knowledge->save();
                if (strlen($data["expertOpinion"]) != 0 && $data["typologyPartId"] != "" )
                { $knowledge->valid = true; }else{ $knowledge->valid = false; }
                $knowledge->save();
            break;
            
            default:
                # code...
        break;
        }
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

    public function update(ZunVersion $zv, Request $request)
    {
        $data = $request->node;
        $id = substr($data["id"],1); 
        $knowledge = Knowledge::findOrFail($id);
        $knowledge->dpp_id = $zv->dpp_id;
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
         if ($data["typologyPartId"] != ""){
             $knowledge->get_dtps()->attach($data["typologyPartId"]);
         }
         switch ($data["justificationType"]) {
             case '0':
                 $knowledge->is_by_expert = 0;
                 $knowledge->save();
                 $knowledge->nsis()->sync($data["nsis"]);
                 if (count($data["nsis"]) > 0 && $data["typologyPartId"] != "" )
                 { $knowledge->valid = true; }else{ $knowledge->valid = false; }
                 $knowledge->save();
             break;
            case '1':
                $knowledge->is_by_expert = 1;
                $knowledge->expert_answer = $data["expertOpinion"];
                $knowledge->save();
                if (strlen($data["expertOpinion"]) != 0 && $data["typologyPartId"] != "" )
                { $knowledge->valid = true; }else{ $knowledge->valid = false; }
                $knowledge->save();
            break;
            
            default:
                # code...
        break;
        }
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
    
    public function destroy(ZunVersion $zv, Request $request)
    {
        $id = substr($request->nodeId,1); 
        $kn = Knowledge::find($id);
        //$kn->nsis()->detach();
        $kn->valid = false;
        $kn->save();

        $kn->links()->detach();
        $kn->get_dtps()->detach();
       
        Knowledge::destroy($id);
        return json_encode("k".$id);
    }

    public function add_new_link(Request $request)
    {
        $knowledge_id = substr($request->nodeId,1); 
        $ability_id = substr($request->abilityId,1);
        $kn = Knowledge::find($knowledge_id);
        $kn->links()->attach($ability_id);
        return response()->json(['message'=>'success'],200);
    }

    public function remove_new_link(Request $request)
    {
        $knowledge_id = substr($request->nodeId,1); 
        $ability_id = substr($request->abilityId,1);
        $kn = Knowledge::find($knowledge_id);
        $kn->links()->detach($ability_id);
        return response()->json(['message'=>'success'],200);
    }
}
