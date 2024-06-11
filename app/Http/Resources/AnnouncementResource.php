<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class AnnouncementResource extends JsonResource
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
            'text' => $this->text,
            'author' => $this->author->fullname,
            'date' => Carbon::parse($this->date)->format('d.m.Y'),
            'isHidden' => $this->is_hidden,
            'isSticked' => $this->is_sticked,
        ];
    }
    
}
