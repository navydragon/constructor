<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\DppUserRole as DppUserRoleResource;
use App\Http\Resources\User as UserResource;
use Auth;

class Dpp extends JsonResource
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
            'abbreveation' =>$this->abbreveation,
            'author' => new UserResource($this->author),
            'type' => $this->type,
            'status' => $this->status,
            'totalHours' => $this->total_hours,
            'created_at' => $this->created_at,
            'current_stage' => $this->current_stage,
            'ish_version_id' => $this->ish_version_id,
            'zun_version_id' => $this->zun_version_id,
            'om_version_id' => $this->om_version_id,
            'st_version_id' => $this->st_version_id,
            'ct_version_id' => $this->ct_version_id,
            'isArchieved' => $this->is_archieved,
            'isDigital' => $this->is_digital,
            'year' => $this->year,
            'participants' => DppUserRoleResource::collection($this->participants),
            'my_role' => optional($this->participants->where('user_id', Auth::user()->id)->first())->role,
        ];
    }
}
