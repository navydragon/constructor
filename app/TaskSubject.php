<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskSubject extends Model
{
    public function ability()
    {
        return $this->belongsTo('App\Ability','ability_id');
    }

    public function skill()
    {
        return $this->belongsTo('App\Skill','skill_id');
    }

    public function objects()
    {
        return $this->hasMany('App\TaskObject','subject_id');
    }
}
