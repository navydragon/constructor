<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MtoType extends Model
{
    public function parent()
    {
        return $this->belongsTo('App\MtoType','parent_id');
    }
}
