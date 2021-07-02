<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nsi extends Model
{
    public function type()
    {
        return $this->belongsTo('App\NsiType','type_id');
    }
}
