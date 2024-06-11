<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends JsonResource
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
            'lastname' => $this->lastname,
            'firstname' => $this->firstname,
            'middlename' => $this->middlename ?? "",
            'fullname' => $this->fullname,
            'email' => $this->email,
            'phone' => $this->phone,
            'isActive' => $this->is_active,
            'rights' => $this->get_rights,
        ];
    }
}
