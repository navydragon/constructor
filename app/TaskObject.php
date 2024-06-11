<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskObject extends Model
{
    protected $appends = array('modelAnswer');
    public function getModelAnswerAttribute(){
        return $this->attributes['model_answer'];
    }
}
