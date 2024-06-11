<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Dpp;
class ZunVersion extends Model
{
    public function dpp()
    {
        return $this->belongsTo('App\Dpp','dpp_id');
    }

    public function competences()
    {
        return $this->hasMany('App\Competence','zun_version_id');
    }

    public function skills()
    {
        return $this->hasMany('App\Skill','zun_version_id');
    }

    public function abilities()
    {
        return $this->hasMany('App\Ability','zun_version_id');
    }

    public function knowledges()
    {
        return $this->hasMany('App\Knowledge','zun_version_id');
    }

    public function get_new_position()
    {
        $competences = $this->competences()->count();
        $skills = $this->skills()->where('competence_id',null)->count();
        $abilities = $this->abilities()->where('competence_id',null)->where('skill_id',null)->get()->count();
        $knowledges = $this->knowledges()->where('ability_id',null)->where('is_through',0)->get()->count();
        $total = $competences + $skills + $abilities + $knowledges;
        return $total;
    }
}
