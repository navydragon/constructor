<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Skill extends Model
{
    use SoftDeletes;

    public static function boot() {
        parent::boot();

        static::deleting(function($skill) { // before delete() method call this
             $skill->moveUpBelow();
             $skill->nsis()->detach();
             $skill->sections()->detach();
             $skill->task_objects()->delete();
             $skill->task_subjects()->delete();
        });
    }

    function nsis () {
        return $this->belongsToMany('App\Nsi', 'skill_nsis', 'skill_id', 'nsi_id');
    }

    public function abilities()
    {
        return $this->hasMany('App\Ability','skill_id')->orderBy('position');
    }

    public function sections()
    {
        return $this->belongsToMany('App\StructureSection', 'skill_section', 'skill_id', 'section_id');
    }

    public function task_subjects()
    {
        return $this->hasMany('App\TaskSubject', 'skill_id');
    }

    public function task_objects()
    {
        return $this->hasManyThrough(
            TaskObject::class,
            TaskSubject::class,
            'skill_id', 
            'subject_id', 
            'id', 
            'id' 
        );
    }

    public function setPositionAndParent($pid)
    {
        if ($pid != '') { $parent_node = substr($pid,1); }else { $parent_node = null;}
        $this->competence_id = $parent_node;
        if ($parent_node == null)
        {
            $used_abilities = Ability::where('zun_version_id',$this->zun_version_id)->where('has_parent_comp',false)->where('skill_id',null)->get()->count();
            $used_skills = Skill::where('zun_version_id',$this->zun_version_id)->where('competence_id',null)->get()->count();
            $used_comps = Competence::where('zun_version_id',$this->zun_version_id)->get()->count();
            $this->position = $used_abilities+$used_skills+$used_comps + 1;
        }else{
            $used_abilities = Ability::where('zun_version_id',$this->zun_version_id)->where('competence_id',$parent_node)->get()->count();
            $used_skills = Skill::where('zun_version_id',$this->zun_version_id)->where('competence_id',$parent_node)->get()->count();
            $this->position = $used_abilities+$used_skills + 1;
        }
    }

    public function moveUpBelow()
    {
        //на верхнем уровне
        if ($this->competence_id == null)
        {
            $below_kns = Knowledge::where('zun_version_id',$this->zun_version_id)
            ->where('ability_id',null)->where('is_through',0)->where('position','>',$this->position)->get();
            $below_abs = Ability::where('zun_version_id',$this->zun_version_id)
            ->where('skill_id',null)->where('competence_id',null)->where('position','>',$this->position)->get();
            $below_sks = Skill::where('zun_version_id',$this->zun_version_id)
            ->where('competence_id',null)->where('position','>',$this->position)->get();
        }
        //внутри компетенции
        else{
            $below_kns = [];
            $below_abs = Ability::where('zun_version_id',$this->zun_version_id)
            ->where('competence_id',$this->competence_id)->where('position','>',$this->position)->get();
            $below_sks = Skill::where('zun_version_id',$this->zun_version_id)
            ->where('competence_id',$this->competence_id)->where('position','>',$this->position)->get();
        }
        foreach ($below_abs as $ability)
        {
            $ability->position = $ability->position-1;
            $ability->save();
        }
        foreach ($below_sks as $skill)
        {
            $skill->position = $skill->position-1;
            $skill->save();
        }
        foreach ($below_kns as $knowledge)
        {
            $knowledge->position = $knowledge->position-1;
            $knowledge->save();
        }        
    }
}
