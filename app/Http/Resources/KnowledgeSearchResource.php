<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class KnowledgeSearchResource extends JsonResource
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
            'dppName' => $this->dpp->name,
            'questions' => count($this->questions),
            'nsis' => count($this->nsis),
            'lection' => isset($this->lection->is_loaded)&&$this->lection->is_loaded == 1 ? true : false
        ];
    }
}
