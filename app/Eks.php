<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Eks extends Model
{
    protected $appends = array('fullName');
    
    public function getFullNameAttribute(){
        return $this->attributes['full_name'];
    }
}
