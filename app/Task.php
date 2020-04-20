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
}
