<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ability;
use App\Skill;
use App\Competence;
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

        $ability = new Ability;
        $ability->dpp_id = $zv->dpp_id;
        $ability->zun_version_id = $zv->id;
        $ability->name = $data["name"];
        $ability->keyword = 'Уметь';
        $ability->what = $data["what"];
        $ability->with = $data["with"];
        $ability->where = $data["where"];
        $ability->position = 0;
        //$ability->save();
        $ability->setPositionAndParent($data["pid"]);
        $ability->save();
        switch ($data["justificationType"]) {
            case '0':
                $ability->is_by_expert = 0;
                $ability->note = $data["note"];
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
        $row["pid"] = $data["pid"] ? $data["pid"] : "c";
        $row["type"] = "Умение";
        $row["valid"] = $ability->valid ? 0 : 1;
        $row["what"] = $ability->what;
        $row["where"] = $ability->where;
        $row["with"] = $ability->with;
        $row["position"] = $ability->position;
        $row["tags"][0] = 'ability';
        $row["nsis"] = $ability->nsis()->pluck('id');
        $row["justificationType"] = $ability->is_by_expert;
        $row["expertOpinion"] = $ability->expert_answer;
        $row["note"] = $ability->note;
        return json_encode($row);
    }

    public function update(ZunVersion $zv,$id, Request $request)
    {
        $data = $request->node;
        $id = substr($id,1); 
        $ability = Ability::findOrFail($id);

        $ability->name = $data["name"];
        $ability->keyword = 'Уметь';
        $ability->what = $data["what"];
        $ability->with = $data["with"];
        $ability->where = $data["where"];
        $ability->save();
        switch ($data["justificationType"]) {
            case '0':
                $ability->is_by_expert = 0;
                $ability->note = $data["note"];
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
        $row["valid"] = $ability->valid ? 0 : 1;
        $row["what"] = $ability->what;
        $row["where"] = $ability->where;
        $row["with"] = $ability->with;
        $row["position"] = $ability->position;
        $row["tags"][0] = 'ability';
        $row["nsis"] = $ability->nsis()->pluck('id');
        $row["justificationType"] = $ability->is_by_expert;
        $row["expertOpinion"] = $ability->expert_answer;
        $row["note"] = $ability->note;
        return json_encode($row);
    }

    public function destroy(ZunVersion $zv, Request $request)
    {
        $id = substr($request->nodeId,1); 
        $ab = Ability::find($id);
        Ability::destroy($id);
        return json_encode("a".$id);
    }
}
