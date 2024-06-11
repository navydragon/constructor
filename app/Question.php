<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{

    protected  $appends = ['questionType'];

    public function getQuestionTypeAttribute()
    {
        switch ($this->question_type_id)
        {
            case 1: return "one-answer"; break;
            case 2: return "multi-answer"; break;
            case 3: return "open-answer"; break;
            case 4: return "sequence-answer"; break;
            case 5: return "conformity-answer"; break;
        }
        return "no";

    }

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
