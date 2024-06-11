<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DppUserRole extends JsonResource
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
            'userId' => $this->user_id,
            'userFullname' => $this->user->fullname,
            'roleId' => $this->role_id,
            'roleName' => $this->role->name,
            'phone' => $this->user->phone,
            'email' => $this->user->email,
            'dppId' => $this->dpp_id,
        ];
    }
}
