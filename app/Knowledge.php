<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Knowledge extends Model
{
    function questions () {
        return $this->hasMany('App\Question','knowledge_id');
    }

    function nsis () {
        return $this->belongsToMany('App\Nsi', 'knowledge_nsis', 'knowledge_id', 'nsi_id');
    }

    public function links()
    {
        return $this->belongsToMany('App\Ability', 'knowledge_links', 'knowledge_id', 'ability_id');
    }
}
