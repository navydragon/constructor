<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LectionResource extends JsonResource
{
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
            'name' => $this->name,
            'additional_files' => $this->additional_files,
            'ct_version_id' => $this->ct_version_id,
            'section_id' => $this->section_id,
            'type' => $this->type,
            'is_loaded' => $this->is_loaded,
            'superviser' => $this->superviser,
            'superviserFullname' => $this->superviser ? $this->by_superviser->fullname : "",
            'normocontroller' => $this->normocontroller,
            'normocontrollerFullname' => $this->normocontroller ? $this->by_normocontroller->fullname : "",
        ];
    }
}
