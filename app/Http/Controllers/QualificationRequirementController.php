<?php

namespace App\Http\Controllers;

use App\QualificationRequirement;
use App\IshVersion;
use App\Http\Resources\QualificationRequirement as QualificationRequirementResource;
use Illuminate\Http\Request;

class QualificationRequirementController extends Controller
{
    
    public function index(IshVersion $iv)
    {
        return QualificationRequirementResource::collection($iv->qualification_requirements);
    }

    public function store(IshVersion $iv, Request $request)
    {
        $qr = new QualificationRequirement;
        $qr->text = $request->input('text');
        $qr->ish_version_id = $iv->id;
        $qr->position = $iv->qualification_requirements->count() + 1;
        $qr->save();
        
        return new QualificationRequirementResource($qr);
    }

    public function show(IshVersion $iv, QualificationRequirement $qualificationRequirement)
    {
        return new QualificationRequirementResource($qualificationRequirement);
    }


    public function update(Request $request, IshVersion $iv, QualificationRequirement $qualificationRequirement )
    {
        $qualificationRequirement->text = $request->input("text");
        $qualificationRequirement->save();
        return new QualificationRequirementResource($qualificationRequirement);
    }

    public function destroy(Request $request, IshVersion $iv, QualificationRequirement $qualificationRequirement )
    {
        $below_qrs = $iv->qualification_requirements->where('position','>',$qualificationRequirement->position);
        foreach ($below_qrs as $item)
        {
            $item->position -= 1;
            $item->save();
        }
        $qualificationRequirement->delete();
        return $qualificationRequirement->id;
    }

    public function reorder(Request $request, IshVersion  $iv)
    {
        $qrs = $request->qualification_requirements;
        $position = 1;
        foreach ($qrs as $id)
        {
            $qr = QualificationRequirement::find($id);
            $qr->position = $position;
            $qr->save();
            $position++;
        }

        return QualificationRequirementResource::collection($iv->qualification_requirements);
    }
}
