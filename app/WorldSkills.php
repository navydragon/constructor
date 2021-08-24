<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorldSkills extends Model
{
    protected $appends = array('fullName');
    
    public function getFullNameAttribute(){
        return $this->attributes['full_name'];
    }

    function dpps () 
    {
        return $this->belongsToMany('App\IshVersion', 'dpp_world_skills', 'world_skill_id','ish_version_id');
    }
}
