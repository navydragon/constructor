<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ZunVersion;
use App\Competence;
use App\Skill;
use App\Ability;

class CompetenceController extends Controller
{
    public function show ($co)
    {
        if (is_numeric($co[0])) {$id = $co;}else{$co = substr($co,1);}
        $co = Competence::find($id);
        return $co;
    }

    public function store(ZunVersion $zv, Request $request)
    {
        //$data = $request->competence_data; 
        $data = $request->node;
        $comp = new Competence;
        $comp->dpp_id = $zv->dpp_id;
        $comp->zun_version_id = $zv->id;
        $comp->name = $data["name"];
        $comp->keyword = "Копетенция";
        $comp->what = $data["what"];
        $comp->with = $data["with"];
        $comp->where = $data["where"];
        $comp->valid = true;
        $comp->save();
        foreach ($request->nodesId as $elem)
        {
            $type = substr($elem, 0, 1);
            $elem = substr($elem,1);
            if ($type == 's')
            {
                $sk = Skill::find($elem);
                $sk->competence_id = $comp->id;
                $sk->save();
            }elseif ($type == 'a'){
                $ab = Ability::find($elem);
                $ab->competence_id = $comp->id;
                $ab->has_parent_comp = true;
                $ab->save();
            }
        }
        
        $row = [];
        $row["id"] = 'c'.$comp->id;
        $row["name"] = $comp->name;
        $row["pid"] = "";
        $row["type"] = "Компетенция";
        $row["valid"] = $comp->valid;
        $row["what"] = $comp->what;
        $row["position"] = $comp->position;
        $row["tags"][0] = 'competence';
        return json_encode($row);
    }

    public function destroy(ZunVersion $zv, Request $request)
    {
        $id = substr($request->nodeId,1); 
        Competence::destroy($id);
        return json_encode("c".$id);
    }

    public function update(ZunVersion $zv, Request $request)
    {
        $data = $request->node;
        $id = substr($data["id"],1);
        $competence = Competence::findOrFail($id);

        $competence->name = $request->competence_name;
        $competence->keyword = $data["keyword"];
        $competence->what = $data["what"];
        $competence->with = $data["with"];
        $competence->where = $data["where"];
        $competence->save();
        
        $row = [];
        $row["id"] = 'c'.$comp->id;
        $row["name"] = $comp->name;
        $row["pid"] = "";
        $row["type"] = "Компетенция";
        $row["valid"] = $comp->valid;
        $row["what"] = $comp->what;
        $row["position"] = $comp->position;
        $row["tags"][0] = 'competence';
        return json_encode($row);
    }
}
