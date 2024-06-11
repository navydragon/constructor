<?php

namespace App\Http\Controllers;

use App\ProfessionalObject;
use App\IshVersion;
use App\Http\Resources\ProfessionalObject as ProfessionalObjectResource;
use Illuminate\Http\Request;

class ProfessionalObjectController extends Controller
{
    public function index(IshVersion $iv)
    {
        return ProfessionalObjectResource::collection($iv->professional_objects);
    }

    public function store(IshVersion $iv, Request $request)
    {
        $item = new ProfessionalObject;
        $item->text = $request->input('text');
        $item->ish_version_id = $iv->id;
        $item->position = $iv->professional_objects->count() + 1;
        $item->save();
        
        return new ProfessionalObjectResource($item);
    }

    public function show(IshVersion $iv, ProfessionalObject $qpo)
    {
        return new ProfessionalObjectResource($qpo);
    }


    public function update(Request $request, IshVersion $iv, ProfessionalObject $qpo )
    {
        $qpo->text = $request->input("text");
        $qpo->save();
        return new ProfessionalObjectResource($qpo);
    }

    public function destroy(Request $request, IshVersion $iv, ProfessionalObject $qpo )
    {
        $below_items = $iv->professional_objects->where('position','>',$qpo->position);
        foreach ($below_items as $item)
        {
            $item->position -= 1;
            $item->save();
        }
        $qpo->delete();
        return $qpo->id;
    }

    public function reorder(Request $request, IshVersion  $iv)
    {
        $items = $request->qualification_professional_objects;
        $position = 1;
        foreach ($items as $id)
        {
            $item = ProfessionalObject::find($id);
            $item->position = $position;
            $item->save();
            $position++;
        }

        return ProfessionalObjectResource::collection($iv->professional_objects);
    }
}
