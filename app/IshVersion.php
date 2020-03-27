<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IshVersion extends Model
{
    function prof_levels () {
        return $this->belongsToMany('App\ProfLevel', 'dpp_prof_levels', 'ish_version_id', 'prof_level_id');
    }

    public function nsis()
    {
        return $this->hasMany('App\Nsi','ish_version_id');
    }

    public function typology_parts()
    {
        return $this->hasMany('App\DppTypologyPart','ish_version_id')->orderBy('position','asc');
    }
}
