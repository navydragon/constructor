<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fgos extends Model
{
    public function fgos_level()
    {
        return $this->belongsTo('App\FgosLevel','fgos_level_id');
    }
}
