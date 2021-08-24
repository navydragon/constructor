<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Eks extends Model
{
    function dpps () 
    {
        return $this->belongsToMany('App\IshVersion', 'dpp_eks', 'eks_id','ish_version_id');
    }
}
