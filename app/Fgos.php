<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fgos extends Model
{
    protected $fillable = ['code'];

    public function fgos_level()
    {
        return $this->belongsTo('App\FgosLevel','fgos_level_id');
    }

    function dpps () 
    {
        return $this->belongsToMany('App\IshVersion', 'dpp_fgoses', 'fgos_id','ish_version_id');
    }
}
