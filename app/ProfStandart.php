<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfStandart extends Model
{
    protected $hidden = ['fullName'];
    
    function dpps () 
    {
        return $this->belongsToMany('App\IshVersion', 'dpp_prof_standarts', 'prof_standart_id','ish_version_id');
    }
}
