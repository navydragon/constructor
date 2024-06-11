<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\NsiType;
class Nsi extends Model
{
    
    protected $appends = array('typeName');

    public function getTypeNameAttribute(){
        return $this->type->name;
    }

    public function type()
    {
        return $this->belongsTo('App\NsiType','type_id');
    }

    public function skills()
    {
        return $this->belongsToMany('App\Skill','skill_nsis','nsi_id','skill_id');
    }

    public function abilities()
    {
        return $this->belongsToMany('App\Ability','ability_nsis','nsi_id','ability_id');
    }

    public function knowledges()
    {
        return $this->belongsToMany('App\Knowledge','knowledge_nsis','nsi_id','knowledge_id');
    }

    public function tasks()
    {
        return $this->belongsToMany('App\Task','task_nsis','nsi_id','task_id');
    }

    
}
