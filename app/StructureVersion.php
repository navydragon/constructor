<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StructureVersion extends Model
{
    public function get_sections()
    {
        return $this->hasMany('App\StructureSection','st_version_id');
    }
}
