<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MultiChoiceAnswer extends Model
{
    protected $appends = array('isCorrect');
    
    public function getIsCorrectAttribute(){
        return $this->attributes['is_right'];
    }
}
