<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\MinistryResource;
use App\Http\Resources\Typology as TypologyResource;
use App\Http\Resources\ProfessionalFieldResource;
use App\Http\Resources\QualificationRequirement as QualificationRequirementResource;
use App\Http\Resources\ProfessionalObject as ProfessionalObjectResource;
use App\Ministry;
use App\Typology;
use App\DigitalSphere;
use App\Nsi;
use App\NsiType;
use App\ProfessionalField;

class IshVersionResource extends JsonResource
{


    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'req_user_edulevel' => is_null($this->req_user_edulevel) ? "": $this->req_user_edulevel,
            'req_qualification' => is_null($this->req_user_kval) ? "": $this->req_user_kval,
            'qualification' => is_null($this->qualification) ? "": $this->qualification,
            'target' => $this->target,
            'annotationDescription' => $this->annotationDescription,
            'prof_standarts' => $this->prof_standarts,
            'dolg_kvals' => $this->dolg_kvals,
            'fgoses' => $this->fgoses,
            'ektses' => $this->ektses,
            'ekses' => $this->ekses,
            'world_skills' => $this->world_skills,
            'corporate_requirements' => $this->corporate_requirements,
            'nsis' => Nsi::where('ish_version_id',$this->id)->with(['type' => function ($q){
                     $q->orderBy('position');
            }])->get(),
            'pl' => $this->prof_levels->pluck('id'),
            'typologies'=> TypologyResource::collection(Typology::all()),
            'total_hours' => $this->dpp->total_hours,
            'type' => $this->dpp->dpp_type_id,
            'typology' => $this->typology_id,
            'typology_parts' => $this->typology_parts,
            'prof_levels' => $this->prof_levels,
            'edu_period_name' => $this->edu_period_name,
            'edu_period_duration' => $this->edu_period_duration,
            'edu_form'=> is_null($this->edu_form) ? "": $this->edu_form,
            'edu_form_dot' => $this->edu_form_dot,
            'edu_practic' => $this->edu_practic,
            'ministries' => MinistryResource::collection(Ministry::all()),
            'direction' => new ProgramDirectionResource($this->direction),
            'qualification_requirements' => QualificationRequirementResource::collection($this->qualification_requirements),
            'qualification_professional_objects' => ProfessionalObjectResource::collection($this->professional_objects),
            'qualification_professional_field' => new ProfessionalFieldResource($this->professional_field),
            'qualification_professional_field_parts' => ProfessionalFieldResource::collection(ProfessionalField::all()),
            'qualification_professional_sphere' => $this->professional_sphere,
            'digital_sphere' => new DigitalSphereResource($this->digital_sphere),
            'digital_spheres' => DigitalSphereResource::collection(DigitalSphere::all()),
        ];
    }
}
