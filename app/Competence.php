<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Ability;
use App\Skill;
class Competence extends Model
{
    use SoftDeletes;

    public function skills()
    {
        return $this->hasMany('App\Skill','competence_id')->orderBy('position');
    }
    public function abilities()
    {
        return $this->hasMany('App\Ability','competence_id')->orderBy('position');
    }

    public function knowledges()
    {
      //  $ability_ids = Ability::where('competence_id',)
      //  return $this->hasMany('App\Ability','competence_id')->orderBy('position');
    }
}
