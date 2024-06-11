<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskStepResource extends JsonResource
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
          'position' => $this->position,
          'text' => $this->text,
          'task_id' => $this->task_id,
          'object' => $this->object,
          'answer' => $this->rightAnswer,
          'advice' => $this->help
        ];
    }
}
