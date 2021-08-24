<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ekts extends Model
{
    function dpps () 
    {
        return $this->belongsToMany('App\IshVersion', 'dpp_ekts', 'ekts_id','ish_version_id');
    }
}
