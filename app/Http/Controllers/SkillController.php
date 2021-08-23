<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ZunVersion;
use App\Skill;

class SkillController extends Controller
{
    public function show($sk)
    {
        if (is_numeric($sk[0])) {$id = $sk;}else{$id = substr($sk,1);}
        $sk = Skill::find($id);
        $sk->nsis = array_column($sk->nsis()->get()->toArray(),'id');
        $sk->valid = true;
        //dd($sk->nsis);
        return $sk;
    }

    public function store(ZunVersion $zv, Request $request)
    {
        $data = $request->node;
        if ($data["pid"] != '') { $parent_node = substr($data["pid"],1); }else { $parent_node = null;}
        $skill = new Skill;
        $skill->dpp_id = $zv->dpp_id;
        $skill->zun_version_id = $zv->id;
        $skill->name = $data["name"];
        $skill->keyword = "Уметь";
        $skill->what = $data["what"];
        $skill->with = $data["with"];
        $skill->where = $data["where"];
        $skill->competence_id = $parent_node;
        $skill->save();
        
        switch ($data["justificationType"]) {
            case '0':
                $skill->is_by_expert = 0;
                $skill->save();
                $skill->nsis()->sync($data["nsis"]);
                if (count($data["nsis"]) > 0) { $skill->valid = true; }else{ $skill->valid = false; }
                $skill->save();
            break;

            case '1':
                $skill->is_by_expert = 1;
                $skill->expert_answer = $data["expertOpinion"];
                $skill->save();
                if (strlen($data["expertOpinion"]) != 0) { $skill->valid = true; }else{ $skill->valid = false; }
                $skill->save();
            break;
            
            default:
                $skill->valid = false;
                $skill->save();
            break;
        }
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

    public function update(ZunVersion $zv, Request $request)
    {
        $data = $request->node;
        $id = substr($data["id"],1);
        if ($data["pid"] != '') { $parent_node = substr($data["pid"],1); }else { $parent_node = null;}
        $skill = Skill::findOrFaill($id);
        $skill->dpp_id = $zv->dpp_id;
        $skill->zun_version_id = $zv->id;
        $skill->name = $data["name"];
        $skill->keyword = "Уметь";
        $skill->what = $data["what"];
        $skill->with = $data["with"];
        $skill->where = $data["where"];
        $skill->competence_id = $parent_node;
        $skill->save();
        
        switch ($data["justificationType"]) {
            case '0':
                $skill->is_by_expert = 0;
                $skill->save();
                $skill->nsis()->sync($data["nsis"]);
                if (count($data["nsis"]) > 0) { $skill->valid = true; }else{ $skill->valid = false; }
                $skill->save();
            break;

            case '1':
                $skill->is_by_expert = 1;
                $skill->expert_answer = $data["expertOpinion"];
                $skill->save();
                if (strlen($data["expertOpinion"]) != 0) { $skill->valid = true; }else{ $skill->valid = false; }
                $skill->save();
            break;
            
            default:
                $skill->valid = false;
                $skill->save();
            break;
        }
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

    public function destroy(ZunVersion $zv, Request $request)
    {
        $id = substr($request->nodeId,1); 
        $sk = Skill::find($id);
        $sk->nsis()->detach();
        Skill::destroy($id);
        return json_encode("s".$id);
    }
}
