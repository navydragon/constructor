<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Competence extends Model
{
    public function skills()
    {
        return $this->hasMany('App\Skill','competence_id')->orderBy('position');
    }
    public function abilities()
    {
        return $this->hasMany('App\Ability','competence_id')->orderBy('position');
    }
}
