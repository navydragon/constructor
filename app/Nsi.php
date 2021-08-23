<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nsi extends Model
{
    public function type()
    {
        return $this->belongsTo('App\NsiType','type_id');
    }

    public function skills()
    {
        return $this->belongsToMany('App\Skill','skill_nsis','nsi_id','skill_id');
    }

    public function abilities()
    {
        return $this->belongsToMany('App\Ability','ability_nsis','nsi_id','ability_id');
    }

    public function knowledges()
    {
        return $this->belongsToMany('App\Knowledge','knowledge_nsis','nsi_id','knowledge_id');
    }

    
}
