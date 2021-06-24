<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ability extends Model
{
    function nsis () {
        return $this->belongsToMany('App\Nsi', 'ability_nsis', 'ability_id', 'nsi_id');
    }
    public function knowledges()
    {
        return $this->hasMany('App\Knowledge','ability_id')->orderBy('position');
    }
    public function sections()
    {
        return $this->belongsToMany('App\StructureSection', 'ability_section', 'ability_id', 'section_id');
    }
}
