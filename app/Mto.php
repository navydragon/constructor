<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mto extends Model
{
    protected $appends = array('typeName','typeId','parentId','unit','count',);
    protected $hidden = array('measure','quantity');

    public function getTypeIdAttribute()
    {
        return $this->attributes["type_id"];
    }
    
    public function getUnitAttribute()
    {
        return $this->attributes["measure"];
    }
    public function getCountAttribute()
    {
        return $this->attributes["quantity"];
    }
    public function getParentIdAttribute()
    {
        if ($this->type_id > 7)
        {
            return $this->type->parent_id;
        }else{
            return $this->type->id;
        }
    }
    public function getTypeNameAttribute()
    {
        if ($this->type_id < 8)
        {
            return $this->type->name;
        }elseif ($this->type_id < 11)
        {
            return $this->type->parent->name."(".$this->type->name.")";
        }
        
    }

    public function tasks()
    {
        return $this->belongsToMany('App\Task','task_mtos','mto_id','task_id');
    }

    public function type()
    {
        return $this->belongsTo('App\MtoType','type_id');
    }
}
