<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Role;
use App\Mto;
class Dpp extends Model
{
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
        $participants =  $this->hasMany('App\DppUserRole','dpp_id');
        return $participants;
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

    public function ish_versions()
    {
        return $this->hasMany('App\IshVersion','dpp_id');
    }

    public function om_versions()
    {
        return $this->hasMany('App\OmVersion','dpp_id');
    }

    public function st_versions()
    {
        return $this->hasMany('App\StructureVersion','dpp_id');
    }
    public function ct_versions()
    {
        return $this->hasMany('App\ContentVersion','dpp_id');
    }

    public function knowledges()
    {
        return $this->hasMany('App\Knowledge','dpp_id');
    }

    public function typology_parts()
    {
        return $this->hasMany('App\DppTypologyPart','dpp_id');
    }

    public function add_base_mtos()
    {
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
        $mto->note = "компьютер с лицензионным программным обеспечением, должен удовлетворять минимальным системным требованиям специализированного ПО, обеспечивать возможность отображения информации на большой экран.";
        $mto->type_id = 13; $mto->dpp_id = $this->id;  $mto->position = 1;
        $mto->is_base_for_lection = true; $mto->is_base_for_practice = false; $mto->save();

        $mto = new Mto();
        $mto->name = "Персональные компьютеры для обучающихся с веб-камерой, доступ к сети Интернет";
        $mto->measure = "шт."; $mto->quantity = 30;
        $mto->note = "компьютер с лицензионным программным обеспечением, должен удовлетворять минимальным системным требованиям специализированного ПО.  Количество компьютеров по количеству обучающихся.";
        $mto->type_id = 13; $mto->dpp_id = $this->id;  $mto->position = 2;
        $mto->is_base_for_lection = true; $mto->is_base_for_practice = false; $mto->save();

        $mto = new Mto();
        $mto->name = "Мультимедиа-комплекс";
        $mto->measure = "шт."; $mto->quantity = 1;
        $mto->note = "возможность отображения информации на большой экран";
        $mto->type_id = 13; $mto->dpp_id = $this->id;  $mto->position = 3;
        $mto->is_base_for_lection = false; $mto->is_base_for_practice = false; $mto->save();

        $mto = new Mto();
        $mto->name = "Периферийное оборудование для ПК (принтер, сканер, сетевое оборудование, интерактивная доска)";
        $mto->measure = "шт."; $mto->quantity = 1;
        $mto->note = "";
        $mto->type_id = 13; $mto->dpp_id = $this->id;  $mto->position = 4;
        $mto->is_base_for_lection = false; $mto->is_base_for_practice = false; $mto->save();

        $mto = new Mto();
        $mto->name = "Бумага";
        $mto->measure = "уп."; $mto->quantity = 1;
        $mto->note = "";
        $mto->type_id = 4; $mto->dpp_id = $this->id;  $mto->position = 1;
        $mto->is_base_for_lection = false; $mto->is_base_for_practice = false; $mto->save();

        $mto = new Mto();
        $mto->name = "Ручки";
        $mto->measure = "уп."; $mto->quantity = 1;
        $mto->note = "";
        $mto->type_id = 4; $mto->dpp_id = $this->id;  $mto->position = 2;
        $mto->is_base_for_lection = false; $mto->is_base_for_practice = false; $mto->save();

        $mto = new Mto();
        $mto->name = "Лицензионное программное обеспечение (Microsoft Office)";
        $mto->measure = "шт."; $mto->quantity = 1;
        $mto->note = "программное обеспечение по количеству персональных компьютеров";
        $mto->type_id = 15; $mto->dpp_id = $this->id;  $mto->position = 1;
        $mto->is_base_for_lection = false; $mto->is_base_for_practice = false; $mto->save();

        $mto = new Mto();
        $mto->name = "Тестирующий программный комплекс системы";
        $mto->measure = "шт."; $mto->quantity = 1;
        $mto->note = "создание библиотеки контрольных вопросов различных типов; формирование тестов на основе библиотеки вопросов (с возможностью случайной выборки, ограничениями по времени и другими параметрами); включение тестов в состав электронных курсов; назначение тестов в качестве самостоятельных оценочных процедур; детальная аналитика по итогам тестирования";
        $mto->type_id = 16; $mto->dpp_id = $this->id;  $mto->position = 1;
        $mto->is_base_for_lection = false; $mto->is_base_for_practice = false; $mto->save();

        $mto = new Mto();
        $mto->name = "Информационно-телекоммуникационные сети";
        $mto->measure = ""; $mto->quantity = 1;
        $mto->note = "обеспечивают передачу по линиям связи учебной информации и обратную связь между обучающимся и средством обучения";
        $mto->type_id = 7; $mto->dpp_id = $this->id;  $mto->position = 1;
        $mto->is_base_for_lection = false; $mto->is_base_for_practice = false; $mto->save();

        $mto = new Mto();
        $mto->name = "Библиотека электронных образовательных ресурсов";
        $mto->measure = ""; $mto->quantity = 1;
        $mto->note = "доступ к электронным образовательным ресурсам, контроль знаний обучающихся (тестирование); персональные компьютеры, программа для создания интерактивных и мультимедийных электронных образовательных ресурсов";
        $mto->type_id = 7; $mto->dpp_id = $this->id;  $mto->position = 2;
        $mto->is_base_for_lection = false; $mto->is_base_for_practice = false; $mto->save();
    }
}
