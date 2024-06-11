<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ability extends Model
{
    use SoftDeletes;
 
    public static function boot() {
        parent::boot();

        static::deleting(function($ability) { // before delete() method call this
             $ability->moveUpBelow();
             $ability->nsis()->detach();
             $ability->sections()->detach();
             $ability->task_objects()->delete();
             $ability->task_subjects()->delete();
        });
    }

    function nsis () {
        return $this->belongsToMany('App\Nsi', 'ability_nsis', 'ability_id', 'nsi_id');
    }

    public function dpp()
    {
        return $this->belongsTo('App\Dpp', 'dpp_id');
    }

    public function knowledges()
    {
        return $this->hasMany('App\Knowledge','ability_id')->orderBy('position');
    }
    public function sections()
    {
        return $this->belongsToMany('App\StructureSection', 'ability_section', 'ability_id', 'section_id');
    }

    public function task_subjects()
    {
        return $this->hasMany('App\TaskSubject', 'ability_id');
    }

    public function task_objects()
    {
        return $this->hasManyThrough(
            TaskObject::class,
            TaskSubject::class,
            'ability_id', // Foreign key on the deployments table...
            'subject_id', // Foreign key on the environments table...
            'id', // Local key в главной
            'id' // Local key в
        );
    }

    public function moveUpBelow()
    {
        //на верхнем уровне
        if ($this->has_parent_comp == 0 && $this->skill_id == 0)
        {
            $below_kns = Knowledge::where('zun_version_id',$this->dpp->zun_version_id)
            ->where('ability_id',null)->where('is_through',0)->where('position','>',$this->position)->get();
            $below_abs = Ability::where('zun_version_id',$this->dpp->zun_version_id)
            ->where('skill_id',null)->where('competence_id',null)->where('position','>',$this->position)->get();
            $below_sks = Skill::where('zun_version_id',$this->dpp->zun_version_id)
            ->where('competence_id',null)->where('position','>',$this->position)->get();
        }
        // внутри навыка
        else if ($this->has_parent_comp == 0)
        {
            $below_kns = [];
            $below_abs = Ability::where('zun_version_id',$this->dpp->zun_version_id)
            ->where('skill_id',$this->skill_id)->where('position','>',$this->position)->get();
            $below_sks = [];
        }
        //внутри компетенции
        else{
            $below_kns = [];
            $below_abs = Ability::where('zun_version_id',$this->dpp->zun_version_id)
            ->where('competence_id',$this->competence_id)->where('position','>',$this->position)->get();
            $below_sks = Skill::where('zun_version_id',$this->dpp->zun_version_id)
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

    public function setPositionAndParent($pid)
    {
        if ($pid != '') { $parent_node = substr($pid,1); }else { $parent_node = null; }
        if ($parent_node == null)
        {
            $this->skill_id = null;
            $this->has_parent_comp = false;
            $this->competence_id = null;
            $used_abilities = Ability::where('zun_version_id',$this->zun_version_id)->where('has_parent_comp',false)->where('skill_id',null)->get()->count();
            $used_skills = Skill::where('zun_version_id',$this->zun_version_id)->where('competence_id',null)->get()->count();
            $used_comps = Competence::where('zun_version_id',$this->zun_version_id)->get()->count();
            $this->position = $used_abilities+$used_skills+$used_comps + 1;
        }
        if (substr($pid, 0, 1) == 's')
        {
            $this->skill_id = $parent_node;
            $this->has_parent_comp = false;
            $this->competence_id = null;
            $abs_c = Ability::where('skill_id','=',$parent_node)->get()->count();
            $this->position = $abs_c + 1;
        }
        if (substr($pid, 0, 1) == 'c')
        {
            $this->skill_id = null;
            $this->has_parent_comp = true;
            $this->competence_id = $parent_node;

            $used_abilities = Ability::where('zun_version_id',$this->zun_version_id)->where('competence_id',$parent_node)->get()->count();
            $used_skills = Skill::where('zun_version_id',$this->zun_version_id)->where('competence_id',$parent_node)->get()->count();
            $this->position = $used_abilities + $used_skills + 1;
        }
    }
}
