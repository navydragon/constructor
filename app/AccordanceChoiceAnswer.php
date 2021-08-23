<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccordanceChoiceAnswer extends Model
{
    protected $appends = array('isCorrect','firstPart','secondPart');
    
    public function getIsCorrectAttribute(){
        return $this->attributes['is_right'];
    }

    public function getFirstPartAttribute(){
        return $this->attributes['text'];
    }
    public function getSecondPartAttribute(){
        return $this->attributes['text2'];
    }
}
