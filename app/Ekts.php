<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ekts extends Model
{
    protected $appends = array('fullName');
    
    public function getFullNameAttribute(){
        return $this->attributes['full_name'];
    }
}
