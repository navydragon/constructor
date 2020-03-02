<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Question;
use App\OmVersion;
use App\SingleChoiceAnswer;
use App\MultiChoiceAnswer;
use App\FreeChoiceAnswer;
use App\SequenceChoiceAnswer;
use App\AccordanceChoiceAnswer;
use Auth;
class OmVersionController extends Controller
{
    public function add_question(OmVersion $ov,Request $request)
    {
        $q_d = $request["question_data"];
        $q = new Question;
        $q->om_version_id = $ov->id;
        $q->author_id = Auth::user()->id;
        $q->knowledge_id = $q_d["knowledge"]["id"];
        $q->question_type_id = $q_d["type"]["id"];
        $q->text = $q_d["text"];
        $q->save();

        switch ($q_d["type"]["id"]) {
            case 1:
                $arr = $q_d["single_choice_answers"];
                foreach ($arr as $elem)
                {
                    $ans = new SingleChoiceAnswer;
                    $ans->author_id = Auth::user()->id;
                    $ans->question_id = $q->id;
                    $ans->text = $elem["text"];
                    if ($elem["id"] == $q_d["single_choice_right"])
                    {
                        $ans->is_right = true;
                    }else{
                        $ans->is_right = false;
                    }
                    $ans->save();
                }
            break;
            case 2:
                $arr = $q_d["multi_choice_answers"];
                foreach ($arr as $elem)
                {
                    $ans = new MultiChoiceAnswer;
                    $ans->author_id = Auth::user()->id;
                    $ans->question_id = $q->id;
                    $ans->text = $elem["text"];
                    if ( in_array($elem["id"],$q_d["multi_choice_right"]) )
                    {
                        $ans->is_right = true;
                    }else{
                        $ans->is_right = false;
                    }
                    $ans->save();
                }
            break;
            case 3:
                $arr = $q_d["free_choice_answers"];
                foreach ($arr as $elem)
                {
                    $ans = new FreeChoiceAnswer;
                    $ans->author_id = Auth::user()->id;
                    $ans->question_id = $q->id;
                    $ans->text = $elem["text"];
                    $ans->is_right = true;
                    $ans->save();
                }
            break;
            case 4:
                $arr = $q_d["sequence_choice_answers"];
                $n = 1;
                foreach ($arr as $elem)
                {
                    $ans = new SequenceChoiceAnswer;
                    $ans->author_id = Auth::user()->id;
                    $ans->question_id = $q->id;
                    $ans->text = $elem["text"];
                    $ans->is_right = true;
                    $ans->position = $n;
                    $ans->save();
                    $n++;
                }
            break;
            case 5:
                $arr = $q_d["accordance_choice_answers"];
                foreach ($arr as $elem)
                {
                    $ans = new AccordanceChoiceAnswer;
                    $ans->author_id = Auth::user()->id;
                    $ans->question_id = $q->id;
                    $ans->text = $elem["accord1"];
                    $ans->text2 = $elem["accord2"];
                    $ans->is_right = true;
                    $ans->save();
                }
            break;            
            default:
                # code...
            break;
        }
        return $q->id;
    }

    public function update_question(OmVersion $ov,Request $request)
    {
        $q_d = $request["question_data"];
        $q = Question::find($request->question_id);
        $q->om_version_id = $ov->id;
        $q->author_id = Auth::user()->id;
        $q->knowledge_id = $q_d["knowledge"]["id"];
        $q->question_type_id = $q_d["type"]["id"];
        $q->text = $q_d["text"];
        $q->save();
        
        switch ($q["type"]["id"]) {
            case 1:
                $ans_arr = $q->single_choice_answers;
                foreach ($ans_arr as $ans)
                {
                    SingleChoiceAnswer::destroy($ans->id);
                }

                $arr = $q_d["single_choice_answers"];
                foreach ($arr as $elem)
                {
                    $ans = new SingleChoiceAnswer;
                    $ans->author_id = Auth::user()->id;
                    $ans->question_id = $q->id;
                    $ans->text = $elem["text"];
                    if ($elem["id"] == $q_d["single_choice_right"])
                    {
                        $ans->is_right = true;
                    }else{
                        $ans->is_right = false;
                    }
                    $ans->save();
                }
            break;
            case 2:
                $ans_arr = $q->multi_choice_answers;
                foreach ($ans_arr as $ans)
                {
                    MultiChoiceAnswer::destroy($ans->id);
                }
                $arr = $q_d["multi_choice_answers"];
                foreach ($arr as $elem)
                {
                    $ans = new MultiChoiceAnswer;
                    $ans->author_id = Auth::user()->id;
                    $ans->question_id = $q->id;
                    $ans->text = $elem["text"];
                    if ( in_array($elem["id"],$q_d["multi_choice_right"]) )
                    {
                        $ans->is_right = true;
                    }else{
                        $ans->is_right = false;
                    }
                    $ans->save();
                }
            break;
            case 3:
                $ans_arr = $q->free_choice_answers;
                foreach ($ans_arr as $ans)
                {
                    FreeChoiceAnswer::destroy($ans->id);
                }
                $arr = $q_d["free_choice_answers"];
                foreach ($arr as $elem)
                {
                    $ans = new FreeChoiceAnswer;
                    $ans->author_id = Auth::user()->id;
                    $ans->question_id = $q->id;
                    $ans->text = $elem["text"];
                    $ans->is_right = true;
                    $ans->save();
                }
            break;
            case 4:
                $ans_arr = $q->sequence_choice_answers;
                foreach ($ans_arr as $ans)
                {
                    SequenceChoiceAnswer::destroy($ans->id);
                }
                $arr = $q_d["sequence_choice_answers"];
                $n = 1;
                foreach ($arr as $elem)
                {
                    $ans = new SequenceChoiceAnswer;
                    $ans->author_id = Auth::user()->id;
                    $ans->question_id = $q->id;
                    $ans->text = $elem["text"];
                    $ans->is_right = true;
                    $ans->position = $n;
                    $ans->save();
                    $n++;
                }
            break;
            case 5:
                $ans_arr = $q->accordance_choice_answers;
                foreach ($ans_arr as $ans)
                {
                    AccordanceChoiceAnswer::destroy($ans->id);
                }
                $arr = $q_d["accordance_choice_answers"];
                foreach ($arr as $elem)
                {
                    $ans = new AccordanceChoiceAnswer;
                    $ans->author_id = Auth::user()->id;
                    $ans->question_id = $q->id;
                    $ans->text = $elem["accord1"];
                    $ans->text2 = $elem["accord2"];
                    $ans->is_right = true;
                    $ans->save();
                }
            break;            
            default:
                # code...
            break;
        }
        
        return json_encode($q);
    }

    public function delete_question(Request $request)
    {
        $q = Question::find($request->id);
        switch ($q["question_type_id"]) {
            case 1:
                $ans_arr = $q->single_choice_answers;
                foreach ($ans_arr as $ans)
                {
                    SingleChoiceAnswer::destroy($ans->id);
                }
                Question::destroy($q->id);
            break;
            case 2:
                $ans_arr = $q->multi_choice_answers;
                foreach ($ans_arr as $ans)
                {
                    MultiChoiceAnswer::destroy($ans->id);
                }
                Question::destroy($q->id);
            break;
            case 3:
                $ans_arr = $q->free_choice_answers;
                foreach ($ans_arr as $ans)
                {
                    FreeChoiceAnswer::destroy($ans->id);
                }
                Question::destroy($q->id);
            break;
            case 4:
                $ans_arr = $q->sequence_choice_answers;
                foreach ($ans_arr as $ans)
                {
                    SequenceChoiceAnswer::destroy($ans->id);
                }
                Question::destroy($q->id);
            break;
            case 5:
                $ans_arr = $q->accordance_choice_answers;
                foreach ($ans_arr as $ans)
                {
                    AccordanceChoiceAnswer::destroy($ans->id);
                }
                Question::destroy($q->id);
            break;            
            default:
                # code...
            break;
        }
    }

    public function get_question_data(Question $question)
    {
        $q = $question;
        $q->knowledge = $question->knowledge;
        $q->type = $question->type;
        switch ($q["question_type_id"]) {
            case 1: 
                $ans_arr = $q->single_choice_answers; 
                $single_choice_right = $question->single_choice_answers->where('is_right',true)->first()->only(['id']);
                $q->single_choice_right = $single_choice_right["id"];
            break;
            case 2: 
                $ans_arr = $q->multi_choice_answers;
                $multi_choice_right = $question->multi_choice_answers->where('is_right',true)->pluck('id')->toArray();
                $q->multi_choice_right = $multi_choice_right;
            break;
            case 3: $ans_arr = $q->free_choice_answers; break;
            case 4: $ans_arr = $q->sequence_choice_answers; break;
            case 5: 
                $ans_arr = $q->accordance_choice_answers; 
                foreach($ans_arr as $ans)
                {
                    $ans->accord1 = $ans->text;
                    $ans->accord2 = $ans->text2;
                }
            break;            
            default: break;
        }
        $q->ans_arr = $ans_arr;
        return json_encode ($q);
    }
}
