<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class KnowledgeImportResource extends JsonResource
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
            'node' => [
                'id' => 'k'.$this->id,
                'name' => $this->name,
                'pid' => $this->is_through ? 'th' : 'a'.$this->ability_id,
                'type' => 'Знание',
                'valid' => $this->valid ? 0 : 1,
                'what' =>  $this->what,
                'position' => $this->position,
                'tags' => ['knowledge'],
                'dtp' => $this->dtps()->get()->first(),
                'nsis' => $this->nsis()->pluck('id'),
                'justificationType' => $this->is_by_expert,
                'expertOpinion' => $this->expert_answer,
                'note' => $this->note,
                'typologyPartId' => $this->dtps()->pluck('id')->first(),
            ],
            'allNsis' => $this->dpp->ish_version->nsis
        ];
    }
}
