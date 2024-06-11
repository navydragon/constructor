<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public static function boot() {
        parent::boot();

        static::created(function($task) 
        { 
           $task->add_base_mto();
        });
        static::deleting(function($task) 
        { 
           $task->mtos()->detach();
           $task->subjects()->delete();
           $task->additional_files()->delete();
        });
    }

    public function task_type()
    {
        return $this->belongsTo('App\TaskType','task_type_id');
    }

    public function om_version()
    {
        return $this->belongsTo('App\OmVersion','om_version_id');
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

    public function additional_files()
    {
        return $this->hasMany('App\TaskAdditionalFile','task_id');
    }

    public function questions()
    {
        return $this->hasMany('App\TaskQuestion','task_id');
    }

    function nsis () {
        return $this->belongsToMany('App\Nsi', 'task_nsis', 'task_id', 'nsi_id');
    }

    public function mtos()
    {
        return $this->belongsToMany('App\Mto', 'task_mtos', 'task_id', 'mto_id');
    }

    public function add_base_mto()
    {
        $dpp = $this->om_version->dpp;
        $practice_mtos = Mto::where('dpp_id',$dpp->id)->where('is_base_for_practice',true)->get()->pluck('id');
        $this->mtos()->sync($practice_mtos);
    }

    public function steps()
    {
        return $this->hasMany('App\TaskStep','task_id')->orderBy('position');
    }
}
