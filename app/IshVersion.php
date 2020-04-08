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

    function prof_standarts () 
    {
        return $this->belongsToMany('App\ProfStandart', 'dpp_prof_standarts', 'ish_version_id', 'prof_standart_id');
    }

    function dolg_kvals () 
    {
        return $this->belongsToMany('App\DolgKval', 'dpp_dolg_kvals', 'ish_version_id', 'dolg_kval_id');
    }
    function fgoses () 
    {
        return $this->belongsToMany('App\Fgos', 'dpp_fgoses', 'ish_version_id', 'fgos_id');
    }
}
