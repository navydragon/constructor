<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CorporateRequirement extends Model
{
    protected $appends = array('fullName');
    
    public function getFullNameAttribute(){
        return $this->attributes['full_name'];
    }

    function dpps () 
    {
        return $this->belongsToMany('App\IshVersion', 'dpp_corporate_requirements', 'corporate_requirement_id','ish_version_id');
    }
}
