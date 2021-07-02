<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StructureSection extends Model
{
    public function knowledges()
    {
        return $this->belongsToMany('App\Knowledge', 'knowledge_section', 'section_id', 'knowledge_id');
    }

    public function skills()
    {
        return $this->belongsToMany('App\Skill', 'skill_section', 'section_id', 'skill_id');
    }

    public function abilities()
    {
        return $this->belongsToMany('App\Ability', 'ability_section', 'section_id', 'ability_id');
    }

    public function themes()
    {
        return $this->hasMany('App\StructureSection','parent_id');
    }
}
