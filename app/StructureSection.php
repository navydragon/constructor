<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StructureSection extends Model
{
    public function knowledges()
    {
        return $this->belongsToMany('App\Knowledge', 'knowledge_section', 'section_id', 'knowledge_id');
    }

    public function themes()
    {
        return $this->hasMany('App\StructureSection','parent_id');
    }
}
