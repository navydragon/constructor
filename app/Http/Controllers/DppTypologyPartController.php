<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Typology;
use App\TypologyPart;
use App\DppTypologyPart;
use App\IshVersion;

class DppTypologyPartController extends Controller
{
    public function store(IshVersion $iv,Request $request)
    {
        $dtp = New DppTypologyPart;
        $dtp->name = $request->name;
        $dtp->dpp_id = $iv->dpp_id;
        $dtp->typology_id = $iv->typology_id;
        $dtp->ish_version_id = $iv->id;
        $elems = $iv->typology_parts->count();
        $dtp->position = $elems+1;
        $dtp->save();
        return $dtp;
    }

    public function destroy(IshVersion $iv,$id)
    {
        $dtp = DppTypologyPart::find($id);
        $dtp->get_knowledges()->detach();
        DppTypologyPart::destroy($id);
        return $id;
    }

    public function update(IshVersion $iv,DppTypologyPart $dtp,Request $request)
    {
        $dtp = DppTypologyPart::findOrFail($dtp->id);
        $dtp->name = $request->name;
        $dtp->save();
        return $dtp;
    }

    public function choose(IshVersion $iv,Request $request)
    {
        
        $dtps = DppTypologyPart::where('ish_version_id','=',$iv->id)->orderBy('position','asc')->get();
        foreach ($dtps as $dtp)
        {
            $dtp->get_knowledges()->detach();
            DppTypologyPart::destroy($dtp->id);
        }
        $typology = Typology::find($request->id);
        foreach ($typology->typology_parts as $part)
        {
            $dtp = New DppTypologyPart;
            $dtp->name = $part->name;
            $dtp->dpp_id = $iv->dpp_id;
            $dtp->typology_id = $request->id;
            $dtp->ish_version_id = $iv->id;
            $elems = $iv->typology_parts->count();
            $dtp->position = $elems+1;
            $dtp->save();    
        }
        $iv->typology_id = $typology->id;
        $iv->save();
        $res = DppTypologyPart::where('ish_version_id','=',$iv->id)->orderBy('position','asc')->get();
        return $res;
    }

    public function reorder(IshVersion $iv,Request $request)
    {
        $dtps = $request->order;
        $position = 1;
        for ($i = 0; $i < count($dtps); $i++)
        {
            $dtp = DppTypologyPart::findOrFail($dtps[$i]);
            $dtp->position = $position;
            $position++;
            $dtp->save();
        }
        return response()->json(['message'=>'success'],200);
    }
}
