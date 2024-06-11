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
        $comp->keyword = 'Способен';
        $comp->what = $data["what"];
        $comp->with = $data["with"];
        $comp->where = $data["where"];
        $comp->valid = true;
        $comp->save();
        $position = 1;
        foreach ($request->nodesId as $elem)
        {
            $type = substr($elem, 0, 1);
            $elem = substr($elem,1);
            if ($type == 's')
            {
                $sk = Skill::find($elem);
                $sk->competence_id = $comp->id;
                $sk->position = $position;
                $sk->save();
            }elseif ($type == 'a'){
                $ab = Ability::find($elem);
                $ab->competence_id = $comp->id;
                $ab->has_parent_comp = true;
                $ab->position = $position;
                $ab->save();
            }
            $position++;
        }
        
        $row = [];
        $row["id"] = 'c'.$comp->id;
        $row["name"] = $comp->name;
        $row["pid"] = "";
        $row["type"] = "Компетенция";
        $row["valid"] = $comp->valid ? 0 : 1;
        $row["what"] = $comp->what;
        $row["with"] = $comp->with;
        $row["where"] = $comp->where;
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
        $comp = Competence::findOrFail($id);
        $comp->name = $data["name"];
        $comp->keyword = 'Способен';
        $comp->what = $data["what"];
        $comp->with = $data["with"];
        $comp->where = $data["where"];
        $comp->save();
        
        $row = [];
        $row["id"] = 'c'.$comp->id;
        $row["name"] = $comp->name;
        $row["pid"] = "";
        $row["type"] = "Компетенция";
        $row["valid"] = $comp->valid;
        $row["what"] = $comp->what;
        $row["with"] = $comp->with;
        $row["where"] = $comp->where;
        $row["valid"] = $comp->valid ? 0 : 1;
        //$row["position"] = $comp->position;
        $row["tags"][0] = 'competence';
        return json_encode($row);
    }
}
