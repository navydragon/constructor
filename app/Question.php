<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    public function type()
    {
        return $this->belongsTo('App\QuestionType','question_type_id');
    }

    public function knowledge()
    {
        return $this->belongsTo('App\Knowledge','knowledge_id');
    }

    public function single_choice_answers()
    {
        return $this->hasMany('App\SingleChoiceAnswer','question_id');
    }
    public function multi_choice_answers()
    {
        return $this->hasMany('App\MultiChoiceAnswer','question_id');
    }
    public function free_choice_answers()
    {
        return $this->hasMany('App\FreeChoiceAnswer','question_id');
    }
    public function sequence_choice_answers()
    {
        return $this->hasMany('App\SequenceChoiceAnswer','question_id')->orderBy('position');
    }
    public function accordance_choice_answers()
    {
        return $this->hasMany('App\AccordanceChoiceAnswer','question_id');
    }
}
