<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TypologyPart extends JsonResource
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
            'typology_id' => $this->typology_id,
            'name' => $this->name,
            'position' => $this->position,
            'not_necessary' => $this->not_necessary
        ];
    }
}
