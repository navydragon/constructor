<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mto extends Model
{
    public function type()
    {
        return $this->belongsTo('App\MtoType','type_id');
    }
}
