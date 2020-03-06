<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ability extends Model
{
    function nsis () {
        return $this->belongsToMany('App\Nsi', 'ability_nsis', 'ability_id', 'nsi_id');
    }
}
