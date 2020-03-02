<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Knowledge extends Model
{
    function questions () {
        return $this->hasMany('App\Question','knowledge_id');
    }
}
