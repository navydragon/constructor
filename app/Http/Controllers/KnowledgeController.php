<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Knowledge;
use App\ZunVersion;
use App\DppTypologyPart;
use App\StructureSection;
use App\Http\Resources\KnowledgeSearchResource;
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
        $knowledge->name = $this->generateKnowledgeTitle($data["what"]);
        $knowledge->keyword = 'Знать';
        $knowledge->what = $data["what"];
        $knowledge->with = " ";
        $knowledge->where = " ";
        $knowledge->setPositionAndParent($data["pid"]);


        $knowledge->save();
         if ($data["typologyPartId"] != ""){
             $dtp = DppTypologyPart::find($data["typologyPartId"]);
             $dtp->knowledges()->detach($knowledge->id);
             $dtp->knowledges()->attach($knowledge->id, array('position' => $dtp->knowledges()->count()+1));
             $sv = $knowledge->dpp->st_version_id;
             if ($sv != null )
             {
                 $knowledge->add_theme($sv);
             }
         }
         switch ($data["justificationType"]) {
             case '0':
                 $knowledge->is_by_expert = 0;
                 $knowledge->note = $data["note"];
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
            $row["valid"] = $knowledge->valid ? 0 : 1;
            $row["what"] = $knowledge->what;
            $row["position"] = $knowledge->position;
            $row["tags"][0] = 'knowledge';
            $row["dtp"] = $knowledge->dtps()->get()->first();
            $row["nsis"] = $knowledge->nsis()->pluck('id');
            $row["justificationType"] = $knowledge->is_by_expert;
            $row["expertOpinion"] = $knowledge->expert_answer;
            $row["note"] = $knowledge->note;
            $row["typologyPartId"] = $knowledge->dtps()->pluck('id')->first();
        return json_encode($row);
    }

    public function update(ZunVersion $zv,$id, Request $request)
    {
        $data = $request->node;
        $id = substr($id,1);
        $knowledge = Knowledge::find($id);
        $knowledge->name = $this->generateKnowledgeTitle($data["what"]);
        $knowledge->keyword = 'Знать';
        $knowledge->what = $data["what"];
        $knowledge->save();
        if ($data["typologyPartId"] != "")
        {
            $dtp = DppTypologyPart::find($data["typologyPartId"]);
            $old_dtp = $knowledge->dtps()->first();

            if ( ($old_dtp != null) && ( $old_dtp->id != $data["typologyPartId"]) )
            {
                //повышаем нижние
                $position = $old_dtp->pivot->position;
                $below = $old_dtp->knowledges()->wherePivot('position','>',$position)->get();

                foreach ($below as $item)
                {
                    $item->dtps()->updateExistingPivot($old_dtp->id, array('position' => $item->pivot->position - 1), false);
                    $item->theme->decrement('position');
                }

                $knowledge->dtps()->detach();
                $dtp->knowledges()->attach($knowledge->id, array('position' => $dtp->knowledges()->count()+1));
            }else{
                //$dtp = DppTypologyPart::find($data["typologyPartId"]);
               // $knowledge->dtps()->sync($dtp->id, array('position' => $dtp->knowledges()->count()));
               // $knowledge->dtps()->detach();
               if ($old_dtp == null)
               {
                $dtp->knowledges()->attach($knowledge->id, array('position' => $dtp->knowledges()->count()+1));
               }
            }
        }
        //  if ($data["typologyPartId"] != "")
        //  {
        //     $dtp = DppTypologyPart::find($data["typologyPartId"]);
        //     $dtp->knowledges()->attach($knowledge->id, array('position' => $dtp->knowledges()->count()));
        //  }
         switch ($data["justificationType"]) {
             case '0':
                 $knowledge->is_by_expert = 0;
                 $knowledge->note = $data["note"];
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
                //dd($data["typologyPartId"]);
                $knowledge->valid = false;
                $knowledge->save();

            break;
        }



            $row = [];
            $row["id"] = 'k'.$knowledge->id;
            $row["name"] = $knowledge->name;
            $row["pid"] = $data["pid"];
            $row["type"] = "Знание";
            $row["valid"] = $knowledge->valid ? 0 : 1;
            $row["what"] = $knowledge->what;
            $row["position"] = $knowledge->position;
            $row["tags"][0] = 'knowledge';
            $row["dtp"] = $knowledge->dtps()->get()->first();
            $row["nsis"] = $knowledge->nsis()->pluck('id');
            $row["justificationType"] = $knowledge->is_by_expert;
            $row["expertOpinion"] = $knowledge->expert_answer;
            $row["typologyPartId"] = $knowledge->dtps()->pluck('id')->first();
            $row["note"] = $knowledge->note;
        return json_encode($row);
    }

    public function destroy(ZunVersion $zv, Request $request)
    {
        $id = substr($request->nodeId,1);
        $kn = Knowledge::find($id);

        //$kn->nsis()->detach();
        $kn->ability_id = null;
        $kn->valid = false;
        $kn->save();
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

    public function search(ZunVersion $zv,Request $request)
    {
        $result = Knowledge::where('name','LIKE','%'.$request->text.'%')->where('zun_version_id','<>',$zv->id)->get();
        return KnowledgeSearchResource::collection($result);
    }

    function generateKnowledgeTitle($what) {
        // Проверяем, начинается ли строка с буквы
        $firstLetter = mb_strtolower(mb_substr($what, 0, 1, 'UTF-8'));

        if (mb_strlen($firstLetter) > 0 && $firstLetter >= 'а' && $firstLetter <= 'я') {
            // Если начинается с буквы, добавляем пробел после "Знать"
            $knowledgeTitle = "Знать " . $what;
        } else {
            // Если не начинается с буквы, просто склеиваем "Знать" и $what
            $knowledgeTitle = "Знать" . $what;
        }

        return $knowledgeTitle;
    }
}
