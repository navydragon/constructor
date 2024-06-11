<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskSubject extends Model
{
     protected $appends = array('name','type');
     protected $hidden = array('skill');

    public static function boot() {
        parent::boot();

        static::created(function($subject) 
        { 
            $task = Task::find($subject->task_id);
            if ($subject->subject_type_id == 2)
            {
                $zun = $subject->ability;
                $nsis = $zun->nsis;
            }else if ($subject->subject_type_id > 2)
            {
                $zun = $subject->skill;
                $nsis = $zun->nsis;
            }
            foreach ($nsis as $nsi)
            {
                $task->nsis()->syncWithoutDetaching($nsi->id);
            }
        });
        static::deleting(function($subject) 
        { 
           $subject->objects()->delete();
        });
    }
     


     public function objects()
     {
         return $this->hasMany('App\TaskObject','subject_id');
     }


     public function getNameAttribute(){
         switch ($this->attributes['subject_type_id'])
         {
             case 2: return $this->ability->name; break;
             case 3: return $this->skill->name; break;
             case 4: return $this->skill->name;  break;
             default: return 'Обнаружена ошибка с типом предмета'; break;
         }
     }

    public function getTypeAttribute()
    {
        switch ($this->attributes['subject_type_id'])
        {
            case 2: return "Умение"; break;
            case 3: return "Навык";  break;
            case 4: return "Навык и входящие в него умения";  break;
            //default: return "Обнаружена ошибка с типом предмета"; break;
        }
    }


    public function ability()
    {
        return $this->belongsTo('App\Ability','ability_id');
    }

    public function skill()
    {
        return $this->belongsTo('App\Skill','skill_id');
    }

   
}
