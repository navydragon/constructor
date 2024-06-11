<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OmVersion extends Model
{
    public function questions()
    {
        return $this->hasMany('App\Question','om_version_id')->with('knowledge');
    }

    public function tasks()
    {
        return $this->hasMany('App\Task','om_version_id');
    }

    public function dpp()
    {
        return $this->belongsTo('App\Dpp','dpp_id');
    }

    public function recount_ia()
    {
        $required_tasks_time = $this->tasks()->where('required',true)->sum('time');
        $optional_tasks_avg_time = $this->tasks()->where('required',false)->avg('time');
        $tasks_time = $required_tasks_time + $optional_tasks_avg_time * $this->optional_tasks;
        $tests_time = $this->test_questions * 1.5;
        $total_time = $tasks_time + $tests_time;
        $ia_section = $this->dpp->st_version->parent_sections->where('name','Итоговая аттестация')->first();
        $att_time = round($total_time / 45, 2);
        $ia_section->attestation_hours = $att_time;
        $ia_section->total_hours = $att_time;
        $ia_section->save();
    }
}
