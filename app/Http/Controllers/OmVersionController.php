<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Dpp;
use App\Question;
use App\QuestionType;
use App\OmVersion;
use App\Knowledge;
use App\SingleChoiceAnswer;
use App\MultiChoiceAnswer;
use App\FreeChoiceAnswer;
use App\SequenceChoiceAnswer;
use App\AccordanceChoiceAnswer;
use App\Task;
use App\TaskSpecification;
use App\TaskSubjectType;
use App\TaskSubject;
use App\TaskObject;
use App\TaskQuestion;
use Auth;
class OmVersionController extends Controller
{
    public function show(Dpp $dpp,OmVersion $ov)
    {
        $knowledges = $dpp->knowledges()->get();
        foreach ($knowledges as $elem)
        {
            $elem->value = $elem->id;
            $elem->text = $elem->name;
            $kn = Knowledge::find($elem->id);
            $elem->questions = $kn->questions;
            foreach ($elem->questions as $q)
            {
                $q->type_name = $q->type->name;
                switch ($q->type->type)
                {
                    case 'one-answer':
                        $q->answers = $q->single_choice_answers;
                    break;
                    case 'multi-answer':
                        $q->answers = $q->multi_choice_answers;
                    break;
                    case 'open-answer':
                        $q->answers = $q->free_choice_answers;
                    break;
                    case 'sequence-answer':
                        $q->answers = $q->sequence_choice_answers;
                    break;
                    case 'conformity-answer':
                        $q->answers = $q->accordance_choice_answers;
                    break;
                }
                $q->questionType = $q->type->type;
            }
        }
        $tasks = Task::where('om_version_id','=',$ov->id)->get();
        foreach ($tasks as $task)
        {
            $task->name = $task->name.$task->position;
            $task->type_name = $task->task_type->short_name;
        }

        $result = [];
        $result["knowledges"] = $knowledges;
        $result["questionTypes"] = QuestionType::all();
        $result["tasks"] = $tasks;
        return json_encode($result);
    }

    public function question_store(OmVersion $ov,Request $request)
    {
        $q_d = $request["questionData"];
        $q = new Question;
        $q->om_version_id = $ov->id;
        $q->author_id = Auth::user()->id;
        $q->knowledge_id = $q_d["knowledgeId"];
        $qt = QuestionType::where('type',$q_d["questionType"])->get()->first();
        $q->question_type_id = $qt["id"];
        $q->text = $q_d["text"];
        $q->save();
        $q->questionType = $q->type->type;
        $answers = $q_d["answers"];
        switch ($q_d["questionType"]) 
        {
            case 'one-answer':
                foreach ($answers as $elem)
                {
                    $ans = new SingleChoiceAnswer;
                    $ans->author_id = Auth::user()->id;
                    $ans->question_id = $q->id;
                    $ans->text = $elem["text"];
                    $ans->is_right = $elem["isCorrect"];
                    $ans->save();
                }
                $q->answers = $q->single_choice_answers;
            break;
            case 'multi-answer':
                foreach ($answers as $elem)
                {
                    $ans = new MultiChoiceAnswer;
                    $ans->author_id = Auth::user()->id;
                    $ans->question_id = $q->id;
                    $ans->text = $elem["text"];
                    $ans->is_right = $elem["isCorrect"];
                    $ans->save();
                }
                $q->answers = $q->multi_choice_answers;
            break;
            case 'open-answer':
                foreach ($answers as $elem)
                {
                    $ans = new FreeChoiceAnswer;
                    $ans->author_id = Auth::user()->id;
                    $ans->question_id = $q->id;
                    $ans->text = $elem["text"];
                    $ans->is_right = $elem["isCorrect"];
                    $ans->save();
                }
                $q->answers = $q->free_choice_answers;
            break;

            case 'sequence-answer':
                $n = 1;
                foreach ($answers as $elem)
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
                $q->answers = $q->sequence_choice_answers;
            break;

            case 'conformity-answer':
                foreach ($answers as $elem)
                {
                    $ans = new AccordanceChoiceAnswer;
                    $ans->author_id = Auth::user()->id;
                    $ans->question_id = $q->id;
                    $ans->text = $elem["firstPart"];
                    $ans->text2 = $elem["secondPart"];
                    $ans->is_right = true;
                    $ans->save();
                }
                $q->answers = $q->accordance_choice_answers;
            break;
        }

        return $q;
    }

    public function question_update(OmVersion $ov, Question $q, Request $request)
    {
        $q_d = $request["questionData"];

        $q->text = $q_d["text"];
        $q->save();
        $qt = QuestionType::where('type',$q_d["questionType"])->get()->first();
        $q->question_type_id = $qt["id"];
        $q->questionType = $q->type->type;
        
        $answers = $q_d["answers"];
        switch ($q_d["questionType"]) 
        {
            case 'one-answer':
                $ans_arr = $q->single_choice_answers;
                foreach ($ans_arr as $ans)
                {
                    SingleChoiceAnswer::destroy($ans->id);
                }
                foreach ($answers as $elem)
                {
                    $ans = new SingleChoiceAnswer;
                    $ans->author_id = Auth::user()->id;
                    $ans->question_id = $q->id;
                    $ans->text = $elem["text"];
                    $ans->is_right = $elem["isCorrect"];
                    $ans->save();
                }
                $q->answers = $q->single_choice_answers()->get();;
            break;
            case 'multi-answer':
                $ans_arr = $q->multi_choice_answers;
                foreach ($ans_arr as $ans)
                {
                    MultiChoiceAnswer::destroy($ans->id);
                }
                foreach ($answers as $elem)
                {
                    $ans = new MultiChoiceAnswer;
                    $ans->author_id = Auth::user()->id;
                    $ans->question_id = $q->id;
                    $ans->text = $elem["text"];
                    $ans->is_right = $elem["isCorrect"];
                    $ans->save();
                }
                $q->answers = $q->multi_choice_answers()->get();
            break;
            case 'open-answer':
                $ans_arr = $q->free_choice_answers;
                foreach ($ans_arr as $ans)
                {
                    FreeChoiceAnswer::destroy($ans->id);
                }
                foreach ($answers as $elem)
                {
                    $ans = new FreeChoiceAnswer;
                    $ans->author_id = Auth::user()->id;
                    $ans->question_id = $q->id;
                    $ans->text = $elem["text"];
                    $ans->is_right = $elem["isCorrect"];
                    $ans->save();
                }
                $q->answers = $q->free_choice_answers()->get();;
            break;

            case 'sequence-answer':
                $ans_arr = $q->sequence_choice_answers;
                foreach ($ans_arr as $ans)
                {
                    SequenceChoiceAnswer::destroy($ans->id);
                }
                $n = 1;
                foreach ($answers as $elem)
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
                $q->answers = $q->sequence_choice_answers()->get();;
            break;

            case 'conformity-answer':
                $ans_arr = $q->accordance_choice_answers;
                foreach ($ans_arr as $ans)
                {
                    AccordanceChoiceAnswer::destroy($ans->id);
                }
                foreach ($answers as $elem)
                {
                    $ans = new AccordanceChoiceAnswer;
                    $ans->author_id = Auth::user()->id;
                    $ans->question_id = $q->id;
                    $ans->text = $elem["firstPart"];
                    $ans->text2 = $elem["secondPart"];
                    $ans->is_right = true;
                    $ans->save();
                }
                $q->answers = $q->accordance_choice_answers()->get();;
            break;
        }
        
        return $q;
    }

    public function question_destroy(OmVersion $ov, Question $q)
    {
        switch ($q["question_type_id"]) {
            case 1:
                $ans_arr = $q->single_choice_answers;
                foreach ($ans_arr as $ans)
                {
                    SingleChoiceAnswer::destroy($ans->id);
                }
            break;
            case 2:
                $ans_arr = $q->multi_choice_answers;
                foreach ($ans_arr as $ans)
                {
                    MultiChoiceAnswer::destroy($ans->id);
                }
            break;
            case 3:
                $ans_arr = $q->free_choice_answers;
                foreach ($ans_arr as $ans)
                {
                    FreeChoiceAnswer::destroy($ans->id);
                }
            break;
            case 4:
                $ans_arr = $q->sequence_choice_answers;
                foreach ($ans_arr as $ans)
                {
                    SequenceChoiceAnswer::destroy($ans->id);
                }
            break;
            case 5:
                $ans_arr = $q->accordance_choice_answers;
                foreach ($ans_arr as $ans)
                {
                    AccordanceChoiceAnswer::destroy($ans->id);
                }
            break;            
            default:
                # code...
            break;
        }
        Question::destroy($q->id);
        return response()->json(['message'=>'success'],200);
    }


    function task_store (OmVersion $ov,Request $request)
    {
        $doc = $request->task;
        $tasks_count = Task::where("om_version_id","=", $ov->id)->get()->count();
        $task = new Task;
        $task->task_type_id = $doc["type"];
        $task->om_version_id = $ov->id;
        $task->position = $tasks_count+1;
        $task->description = $doc["description"];
        $task->place = $doc["place"];
        $task->time = $doc["time"];
        $task->portfolioStructureReq = $doc["portfolioStructureReq"];
        $task->portfolioPresentationReq = $doc["portfolioPresentationReq"];
        $task->portfolioProcedure = $doc["portfolioProcedure"];
        $task->save();
        return $task;
    }

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
                $q->answers = $q->multi_choice_answers;
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
                $q->answers = $q->free_choice_answers;
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
                $q->answers = $q->sequence_choice_answers;
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

    public function add_task(Request $request)
    {
        $tasks_count = Task::where("om_version_id","=",$request->om_version_id)->get()->count();
        $task = new Task;
        $task->task_type_id = $request->type;
        $task->om_version_id = $request->om_version_id;
        $task->position = $tasks_count+1;
        $task->save();
        $ts = new TaskSpecification;
        $ts->task_id = $task->id;
        $ts->save();
        return $task->id;
    }

    public function remove_task(Request $request)
    {
        $task = Task::find($request->id);
        $task->nsis()->detach();
        foreach ($task->subjects as $subject)
        {
            foreach ($subject->objects as $object)
            {
                TaskObject::destroy($object->id);
            }
            TaskSubject::destroy($subject->id);
        }
        foreach ($task->questions as $question)
        {
            TaskQuestion::destroy($question->id);
        }
        $ts_id = $task->specification->id;
        TaskSpecification::destroy($ts_id);
        Task::destroy($task->id);
    }

    public function get_task_subject_types()
    {
        return TaskSubjectType::all();
    }

    public function get_tasks(OmVersion $ov, Request $request)
    {
        $tasks = Task::where('om_version_id','=',$ov->id)->get();
        foreach ($tasks as $task)
        {
            $task->name = $task->name.$task->position;
            $task->type_name = $task->task_type->short_name;
        }
        return $tasks;
    }

    public function get_task_data(Task $task, Request $request)
    {
        $task->name = $task->name.$task->position;
        $task->type_name = $task->task_type->short_name;
        
        $task->subject_skills = [];
        $task->specification = TaskSpecification::firstOrCreate(['task_id' => $task->id]);
        $task->subjects = $task->subjects;
        foreach ($task->subjects as $subject)
        {
            if ($subject->subject_type_id == 2)
            {
                $subject->name = $subject->ability->name;
            }
            if ($subject->subject_type_id == 3)
            {
                $subject->name = $subject->skill->name;
            }
            if ($subject->subject_type_id == 4)
            {
                $subject->name = $subject->skill->name." (и входящие в навык умения)";
            }
           
        }
        $task->objects = $task->objects;
        $task->questions = $task->questions;
        $arr = [];
        $nsis = $task->nsis()->get();
        foreach ($nsis as $nsi)
        {
            array_push($arr,$nsi->id);
        }
        $task->nsis = $arr;
        return $task;
    }

    public function update_specification(Request $request)
    {
        $t = Task::find($request->task_id);
        $ts = TaskSpecification::firstOrCreate(['task_id' => $request->task_id]);
        $spec = $request->specification;
        $t->nsis()->sync($request->nsis);
        $ts->description = $spec["description"];
        $ts->place = $spec["place"];
        $ts->source = $spec["source"];
        $ts->time = $spec["time"];
        $ts->portfolio_structure_req = $spec["portfolio_structure_req"];
        $ts->portfolio_presentation_req = $spec["portfolio_presentation_req"];
        $ts->portfolio_procedure = $spec["portfolio_procedure"];
        $ts->save();
        return $ts;
    }

    public function add_subject (Request $request)
    {
        $result = [];
        $s = $request->subject;
        if ($s["type_id"] == 2)
        {
            foreach ($s["abilities"] as $ability) {
                $ts = new TaskSubject;
                $ts->task_id = $request->task_id;
                $ts->subject_type_id = $s["type_id"];
                $ts->ability_id = $ability;
                $ts->save();
                $ts->name = $ts->ability->name;
                array_push($result,$ts);
            }
        }
        if ( ($s["type_id"] == 3) || ($s["type_id"] == 4) )
        { 
            $ts = new TaskSubject;
            $ts->task_id = $request->task_id;
            $ts->subject_type_id = $s["type_id"];
            $ts->skill_id = $s["skills"];
            $ts->save();
            $ts->name = $ts->skill->name;
            if ($s["type_id"] == 4)
            {
                $ts->name = $ts->skill->name." (и входящие в навык умения)";;
            }
            array_push($result,$ts);
        } 
        return json_encode($result);   
    }

    public function remove_subject(Request $request)
    {
        $ts = TaskSubject::find($request->id);
        foreach ($ts->objects as $object)
        {
            TaskObject::destroy($object->id);
        }
        TaskSubject::destroy($request->id);
    }

    public function add_object(Request $request)
    {
        $o = $request->object;
        $to = new TaskObject;
        $to->task_id = $request->task_id;
        $to->subject_id = $o["subject_id"];
        $to->name = $o["name"];
        $to->model_answer = $o["model_answer"];
        $to->save();
        return $to;
    }

    public function get_task_object(Request $request)
    {
        $o = TaskObject::find($request->object_id);
        return $o;
    }

    public function update_object(Request $request)
    {
        $o = $request->object;
        $to = TaskObject::find($o["id"]);
        $to->subject_id = $o["subject_id"];
        $to->name = $o["name"];
        $to->model_answer = $o["model_answer"];
        $to->save();
        return $to;
    }

    public function remove_object(Request $request)
    {
        TaskObject::destroy($request->id);
    }

    public function add_task_question(Request $request)
    {
        $q = $request->question;
        $tq = new TaskQuestion;
        $tq->task_id = $request->task_id;
        $tq->name = $q["name"];
        $tq->model_answer = $q["model_answer"];
        $tq->save();
        return $tq;
    }

    public function remove_task_question(Request $request)
    {
        TaskQuestion::destroy($request->id);
    }

    public function get_task_question(Request $request)
    {
        $q = TaskQuestion::find($request->question_id);
        return $q;
    }

    public function update_task_question(Request $request)
    {
        $q = $request->question;
        $tq = TaskQuestion::find($q["id"]);
        $tq->name = $q["name"];
        $tq->model_answer = $q["model_answer"];
        $tq->save();
        return $tq;
    }
}
