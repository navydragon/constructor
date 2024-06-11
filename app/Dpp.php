<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Role;
use App\Mto;
use App\StageType;
use App\DppStage;
use Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dpp extends Model
{
    use SoftDeletes;

    public static function boot() {
        parent::boot();
        static::created(function($dpp) 
        { 
          $dpp->createStages(); 
          $dpp->create_iv(); 
          $dpp->create_zun();
          $dpp->create_om();
          $dpp->create_st();
          $dpp->create_ct();
          $dpp->add_base_mtos();
        });

        static::deleting(function($dpp)
        {
            $dpp->participants()->delete();
        });
    }

    public function createStages()
    {
        $go = true;
        $st = StageType::where('is_first',true)->get()->first();
        while ($go == true)
        {
            $ds = new DppStage;
            $ds->dpp_id = $this->id;
            $ds->stage_type_id = $st->id;
            if ($st->is_first == true){
                $ds->stage_status_id = 1;  
            }else{
                $ds->stage_status_id = 2;
            }
            $ds->save();
            if ($st->is_first == true){
                $this->current_stage_id = $ds->id;
                $this->save();
            }
            if ($st->next_stage == null)
            {
                $go = false;
            }else{
                $st = StageType::find($st->next_stage);
            }
        }
    }

    public function type()
    {
        return $this->belongsTo('App\DppType','dpp_type_id');
    }
    public function status()
    {
        return $this->belongsTo('App\DppStatus','status_id');
    }
    public function participants()
    {
        return  $this->hasMany('App\DppUserRole','dpp_id');
    }

    public function author()
    {
        return $this->belongsTo('App\User','author_id');
    }
    
    public function stages()
    {
        return $this->hasMany('App\DppStage','dpp_id');
    }
    public function current_stage()
    {
        return $this->belongsTo('App\DppStage','current_stage_id');
    }

    public function zun_versions()
    {
        return $this->hasMany('App\ZunVersion','dpp_id');
    }

    public function zun_version()
    {
        return $this->belongsTo('App\ZunVersion','zun_version_id');
    }

    public function ish_versions()
    {
        return $this->hasMany('App\IshVersion','dpp_id');
    }

    public function ish_version()
    {
        return $this->belongsTo('App\IshVersion','ish_version_id');
    }

    public function om_versions()
    {
        return $this->hasMany('App\OmVersion','dpp_id');
    }

    public function om_version()
    {
        return $this->belongsTo('App\OmVersion','om_version_id');
    }

    public function st_versions()
    {
        return $this->hasMany('App\StructureVersion','dpp_id');
    }

    public function st_version()
    {
        return $this->belongsTo('App\StructureVersion','st_version_id');
    }

    public function ct_versions()
    {
        return $this->hasMany('App\ContentVersion','dpp_id');
    }

    public function ct_version()
    {
        return $this->belongsTo('App\ContentVersion','ct_version_id');
    }

    public function knowledges()
    {
        return $this->hasMany('App\Knowledge','dpp_id')->orderBy('name');
    }

    public function zun_knowledges()
    {
        $result = [];
        $zv = $this->zun_version_id;
        $competences = Competence::where('zun_version_id','=',$zv)->get();
        foreach ($competences as $competence)
        {
            $skills = Skill::where('competence_id','=',$competence->id)->orderBy('position','asc')->get();
            foreach ($skills as $skill)
            {
                $abilities = Ability::where('skill_id','=',$skill->id)->orderBy('position','asc')->get();
                foreach ($abilities as $ability)
                {
                    $knowledges = Knowledge::where('ability_id','=',$ability->id)->orderBy('position','asc')->get();
                    foreach ($knowledges as $key=>$knowledge)
                    {
                        array_push($result,$knowledge);
                    }
                }
            }
            $abilities = Ability::where('competence_id','=',$competence->id)->orderBy('position','asc')->get();
            foreach ($abilities as $ability)
            {
                $knowledges = Knowledge::where('ability_id','=',$ability->id)->orderBy('position','asc')->get();
                foreach ($knowledges as $key=>$knowledge)
                {
                    array_push($result,$knowledge);
                }
            }
        }
        $skills = Skill::where('competence_id','=',null)->where('zun_version_id','=',$zv)->orderBy('position','asc')->get();
        foreach ($skills as $skill)
        {
            $abilities = Ability::where('skill_id','=',$skill->id)->get();
            foreach ($abilities as $ability)
            {
                $knowledges = Knowledge::where('ability_id','=',$ability->id)->get();
                foreach ($knowledges as $key=>$knowledge)
                {
                    array_push($result,$knowledge);
                }
            }
        }
        $abilities = Ability::where('competence_id','=',null)->where('skill_id','=',null)->where('zun_version_id','=',$zv)->orderBy('position','asc')->get();
        foreach ($abilities as $ability)
        {
            $knowledges = Knowledge::where('ability_id','=',$ability->id)->get();
            foreach ($knowledges as $key=>$knowledge)
            {
                array_push($result,$knowledge);
            }
        }
       
        $th_knowledges = Knowledge::where('zun_version_id',$zv)->where('is_through',true)->orderBy('position','asc')->get();       
        foreach ($th_knowledges as $key => $knowledge)
        {
            array_push($result,$knowledge);
        }

        return $result;
    }



    public function competences()
    {
        return $this->hasMany('App\Competence','dpp_id')->orderBy('name');
    }

    public function abilities()
    {
        return $this->hasMany('App\Ability','dpp_id');
    }

    public function skills()
    {
        return $this->hasMany('App\Skill','dpp_id');
    }

    public function typology_parts()
    {
        return $this->hasMany('App\DppTypologyPart','dpp_id')->orderBy('position');
    }

    public function designers()
    {
        return $this->hasMany('App\Designer','dpp_id')->orderBy('position');
    }

    public function create_om()
    {
        $om = new OmVersion;
        $om->dpp_id = $this->id;
        $om->author_id = Auth::user()->id;
        $om->save();
        $this->om_version_id = $om->id;
        $this->save();
        return $om;
    }

    public function create_st()
    {
        $st = new StructureVersion;
        $st->dpp_id = $this->id;
        $st->author_id = Auth::user()->id;
        $st->save();
        $this->st_version_id = $st->id;
        $this->save();
        $st->rebuild();
        return $st;
    }

    public function create_iv()
    {
        $iv = new IshVersion;
        $iv->dpp_id = $this->id;
        $iv->author_id = Auth::user()->id;
        $iv->save();
        $this->ish_version_id = $iv->id;
        $this->save();
        return $iv;
    }

    public function create_zun()
    {
        $zv = new ZunVersion;
        $zv->dpp_id = $this->id;
        $zv->author_id = Auth::user()->id;
        $zv->save();
        $this->zun_version_id = $zv->id;
        $this->save();
        return $zv;
    }


    public function create_ct()
    {
        $ct = new ContentVersion;
        $ct->dpp_id = $this->id;
        $ct->author_id = Auth::user()->id;
        $ct->save();
        $this->ct_version_id = $ct->id;
        $this->save();
        return $ct;
    }

    public function mtos()
    {
        return $this->hasMany('App\Mto');
    }

    public function add_base_mtos()
    {
        foreach ($this->mtos as $mto)
        {
            $mto->tasks()->detach();
            Mto::destroy($mto->id);
        }
        $mto = new Mto();
        $mto->name = "Лекционная аудитория";
        $mto->measure = "шт."; $mto->quantity = 1;
        $mto->note = "доска, средства отображения данных на большой экран, доступ в интернет";
        $mto->type_id = 8; $mto->dpp_id = $this->id;  $mto->position = 1;
        $mto->is_base_for_lection = true; $mto->is_base_for_practice = false; $mto->save();

        $mto = new Mto();
        $mto->name = "Аудитория для практических занятий";
        $mto->measure = "шт."; $mto->quantity = 1;
        $mto->note = "доска, средства отображения данных на большой экран, доступ в интернет";
        $mto->type_id = 9; $mto->dpp_id = $this->id;  $mto->position = 1;
        $mto->is_base_for_lection = false; $mto->is_base_for_practice = true; $mto->save();

        $mto = new Mto();
        $mto->name = "Стол";
        $mto->measure = "шт."; $mto->quantity = 30;
        $mto->note = "посадочные места по количеству обучающихся";
        $mto->type_id = 11; $mto->dpp_id = $this->id;  $mto->position = 1;
        $mto->is_base_for_lection = true; $mto->is_base_for_practice = true; $mto->save();

        $mto = new Mto();
        $mto->name = "Стул";
        $mto->measure = "шт."; $mto->quantity = 30;
        $mto->note = "посадочные места по количеству обучающихся";
        $mto->type_id = 11; $mto->dpp_id = $this->id;  $mto->position = 2;
        $mto->is_base_for_lection = true; $mto->is_base_for_practice = true; $mto->save();

        $mto = new Mto();
        $mto->name = "Персональный компьютер преподавателя с веб-камерой, доступ к сети Интернет";
        $mto->measure = "шт."; $mto->quantity = 1;
        $mto->note = "компьютер с лицензионным программным обеспечением, должен удовлетворять минимальным системным требованиям специализированного ПО, обеспечивать возможность отображения информации на большой экран";
        $mto->type_id = 13; $mto->dpp_id = $this->id;  $mto->position = 1;
        $mto->is_base_for_lection = true; $mto->is_base_for_practice = true; $mto->save();

        $mto = new Mto();
        $mto->name = "Персональные компьютеры для обучающихся с веб-камерой, доступ к сети Интернет";
        $mto->measure = "шт."; $mto->quantity = 30;
        $mto->note = "компьютер с лицензионным программным обеспечением, должен удовлетворять минимальным системным требованиям специализированного ПО. Количество компьютеров по количеству обучающихся";
        $mto->type_id = 13; $mto->dpp_id = $this->id;  $mto->position = 2;
        $mto->is_base_for_lection = true; $mto->is_base_for_practice = true; $mto->save();

        $mto = new Mto();
        $mto->name = "Мультимедиа-комплекс";
        $mto->measure = "шт."; $mto->quantity = 1;
        $mto->note = "возможность отображения информации на большой экран";
        $mto->type_id = 13; $mto->dpp_id = $this->id;  $mto->position = 3;
        $mto->is_base_for_lection = true; $mto->is_base_for_practice = true; $mto->save();

        $mto = new Mto();
        $mto->name = "Периферийное оборудование для ПК (принтер, сканер, сетевое оборудование, интерактивная доска)";
        $mto->measure = "шт."; $mto->quantity = 1;
        $mto->note = "";
        $mto->type_id = 13; $mto->dpp_id = $this->id;  $mto->position = 4;
        $mto->is_base_for_lection = true; $mto->is_base_for_practice = true; $mto->save();

        $mto = new Mto();
        $mto->name = "Бумага";
        $mto->measure = "уп."; $mto->quantity = 1;
        $mto->note = "";
        $mto->type_id = 4; $mto->dpp_id = $this->id;  $mto->position = 1;
        $mto->is_base_for_lection = true; $mto->is_base_for_practice = true; $mto->save();

        $mto = new Mto();
        $mto->name = "Ручки";
        $mto->measure = "уп."; $mto->quantity = 1;
        $mto->note = "";
        $mto->type_id = 4; $mto->dpp_id = $this->id;  $mto->position = 2;
        $mto->is_base_for_lection = true; $mto->is_base_for_practice = true; $mto->save();

        $mto = new Mto();
        $mto->name = "Лицензионное программное обеспечение (Microsoft Office)";
        $mto->measure = "шт."; $mto->quantity = 1;
        $mto->note = "программное обеспечение по количеству персональных компьютеров";
        $mto->type_id = 15; $mto->dpp_id = $this->id;  $mto->position = 1;
        $mto->is_base_for_lection = true; $mto->is_base_for_practice = true; $mto->save();

        $mto = new Mto();
        $mto->name = "Тестирующий программный комплекс системы";
        $mto->measure = "шт."; $mto->quantity = 1;
        $mto->note = "создание библиотеки контрольных вопросов различных типов; формирование тестов на основе библиотеки вопросов (с возможностью случайной выборки, ограничениями по времени и другими параметрами); включение тестов в состав электронных курсов; назначение тестов в качестве самостоятельных оценочных процедур; детальная аналитика по итогам тестирования";
        $mto->type_id = 16; $mto->dpp_id = $this->id;  $mto->position = 1;
        $mto->is_base_for_lection = true; $mto->is_base_for_practice = true; $mto->save();

        $mto = new Mto();
        $mto->name = "Информационно-телекоммуникационные сети";
        $mto->measure = ""; $mto->quantity = 1;
        $mto->note = "обеспечивают передачу по линиям связи учебной информации и обратную связь между обучающимся и средством обучения";
        $mto->type_id = 7; $mto->dpp_id = $this->id;  $mto->position = 1;
        $mto->is_base_for_lection = true; $mto->is_base_for_practice = true; $mto->save();

        $mto = new Mto();
        $mto->name = "Библиотека электронных образовательных ресурсов";
        $mto->measure = ""; $mto->quantity = 1;
        $mto->note = "доступ к электронным образовательным ресурсам, контроль знаний обучающихся (тестирование); персональные компьютеры, программа для создания интерактивных и мультимедийных электронных образовательных ресурсов";
        $mto->type_id = 7; $mto->dpp_id = $this->id;  $mto->position = 2;
        $mto->is_base_for_lection = true; $mto->is_base_for_practice = true; $mto->save();

        $this->add_mto_to_tasks();
        return $mto;
    }

    public function add_mto_to_tasks()
    {
        $tasks = Task::where('om_version_id','=',$this->om_version_id)->get();
        $practice_mtos = Mto::where('dpp_id',$this->id)->where('is_base_for_practice',true)->get()->pluck('id');
        foreach ($tasks as $task)
        {
            $task->mtos()->sync($practice_mtos);
        }
    }


    public function setType($hours)
    {
        if ($hours < 250) {return 1;}else{return 2;}
    }

    public function setAbbreveation($name)
    {
        $words = explode(" ", $name);
        $result = "";
        foreach ($words as $value)
        {     
            if (preg_match('/^([а-яА-ЯЁё_]+)$/u',$value))
            {
                if  (mb_strlen($value) > 1)
                {
                    $result .=mb_strtoupper(mb_substr($value,0,1,'UTF-8'));
                }else{
                    $result .= $value;
                }
            }
        }
        return $result;
    }

    public function tasks()
    {
        return $this->hasManyThrough(
            'App\Task',
            'App\OmVersion',
            'dpp_id', 
            'om_version_id', 
            'id', 
            'id'
        );
    }

    public function category()
    {
        return $this->belongsTo('App\DppCategory');
    }

}
