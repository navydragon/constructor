<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DesignerResource extends JsonResource
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
            'dpp_id' => $this->dpp_id,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'middlename' => $this->middlename,
            'title' => $this->title,
            'titleShort' => $this->title_short,
            'degree' => $this->degree,
            'degreeShort' => $this->degree_short,
            'job' => $this->job,
            'task' => $this->task,
            'position' => $this->position,
        ];
    }
}
