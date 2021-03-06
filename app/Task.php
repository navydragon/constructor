<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public function task_type()
    {
        return $this->belongsTo('App\TaskType','task_type_id');
    }

    public function specification()
    {
        return $this->hasOne('App\TaskSpecification','task_id');
    }

    public function subjects()
    {
        return $this->hasMany('App\TaskSubject','task_id');
    }

    public function objects()
    {
        return $this->hasMany('App\TaskObject','task_id');
    }

    public function questions()
    {
        return $this->hasMany('App\TaskQuestion','task_id');
    }

    function nsis () {
        return $this->belongsToMany('App\Nsi', 'task_nsis', 'task_id', 'nsi_id');
    }
}
