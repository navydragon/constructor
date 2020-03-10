<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Typology extends Model
{
    public function typology_parts()
    {
        return $this->hasMany('App\TypologyPart','typology_id');
    }
}
