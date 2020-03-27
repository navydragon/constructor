<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    function nsis () {
        return $this->belongsToMany('App\Nsi', 'skill_nsis', 'skill_id', 'nsi_id');
    }

    public function abilities()
    {
        return $this->hasMany('App\Ability','skill_id')->orderBy('position');
    }
}
