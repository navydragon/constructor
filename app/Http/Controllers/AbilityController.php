<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ability;
use App\ZunVersion;

class AbilityController extends Controller
{
    public function show($ab)
    {
        if (is_numeric($ab[0])) {$id = $ab;}else{$id = substr($ab,1);}
        $ab = Ability::find($id);
        $ab->nsis = array_column($ab->nsis()->get()->toArray(),'id');
        $ab->valid = true;
        //dd($sk->nsis);
        return $ab;
    }

    public function store(ZunVersion $zv, Request $request)
    {
        $data = $request->node;
        if ($data["pid"] != ''){ $parent_node = substr($data["pid"],1); 
        }else { $parent_node = null;} 
        $ability = new Ability;
        $ability->dpp_id = $zv->dpp_id;
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
        switch ($data["justificationType"]) {
            case '0':
                $ability->is_by_expert = 0;
                $ability->save();
                $ability->nsis()->sync($data["nsis"]);
                if (count($data["nsis"]) > 0){ $ability->valid = true; }else{ $ability->valid = false; }
                $ability->save();
            break;

            case '1':
                $ability->is_by_expert = 1;
                $ability->expert_answer = $data["expertOpinion"];
                if (strlen($data["expertOpinion"]) != 0)
                { $ability->valid = true; }else{ $ability->valid = false; }
                $ability->save();
            break;
            
            default:
                $ability->valid = false;
                $ability->save();
            break;
        }
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

    public function update(ZunVersion $zv, Request $request)
    {
        $data = $request->node;
        $id = substr($data["id"],1); 
        if ($data["pid"] != ''){ $parent_node = substr($data["pid"],1); 
        }else { $parent_node = null;} 
        $ability = Ability::findOrFail($id);
        $ability->dpp_id = $zv->dpp_id;
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
        switch ($data["justificationType"]) {
            case '0':
                $ability->is_by_expert = 0;
                $ability->save();
                $ability->nsis()->sync($data["nsis"]);
                if (count($data["nsis"]) > 0){ $ability->valid = true; }else{ $ability->valid = false; }
                $ability->save();
            break;

            case '1':
                $ability->is_by_expert = 1;
                $ability->expert_answer = $data["expertOpinion"];
                if (strlen($data["expertOpinion"]) != 0)
                { $ability->valid = true; }else{ $ability->valid = false; }
                $ability->save();
            break;
            
            default:
                $ability->valid = false;
                $ability->save();
            break;
        }
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

    public function destroy(ZunVersion $zv, Request $request)
    {
        $id = substr($request->nodeId,1); 
        $ab = Ability::find($id);
        $ab->nsis()->detach();
        Ability::destroy($id);
        return json_encode("a".$id);
    }
}
