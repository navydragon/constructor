<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\OmVersion;
use App\Task;
use App\TaskStep;
use App\TaskSubject;
use App\TaskObject;
use App\Dpp;
use App\Mto;
use App\Http\Resources\TaskStepResource;

class TaskController extends Controller
{


    public function store(OmVersion $ov, Request $request)
    {

        $doc = $request->task;
        $tasks_count = Task::where("om_version_id","=", $ov->id)->get()->count();
        $task = new Task;
        $task->task_type_id = $doc["type"];
        if ($doc["type"] == 1 || $doc["type"] == 3)
        {
            $task->om_version_id = $ov->id;
            $task->position = $tasks_count+1;
            $task->description = $doc["description"];
            $task->place = $doc["place"];
            $task->time = $doc["time"];
            $task->required = $doc["required"] ?? true;
            $task->save();
        }
        if ($doc["type"] == 2)
        {
            $task->om_version_id = $ov->id;
            $task->position = $tasks_count+1;
            $task->description = $doc["description"];
            $task->portfolioProcedure = $doc["instruction"];
            $task->portfolioCriteria = $doc["control"];
            $task->required = $doc["required"] ?? true;
            $task->time = $doc["time"];
            $task->save();
        }

        if ($ov->dpp->type->id == 1)
        {
            $ov->recount_ia();
        }



        $task = Task::where('id',$task->id)->with('subjects')->with('nsis')->with('mtos')->get()->first();
        $task->name = $task->name.$task->position;
        $task->type_name = $task->task_type->short_name;
        $task->additional_files = $task->additional_files;
        $task->instruction = $task->portfolioProcedure;
        $task->control = $task->portfolioCriteria;
        $task->taskSteps = $task->steps;
        return $task;
    }

    public function update(OmVersion $ov, Task $task, Request $request)
    {
        $doc = $request->task;
        if ($doc["type"] == 1 || $doc["type"] == 3)
        {
            $task->description = $doc["description"];
            $task->place = $doc["place"];
            $task->time = $doc["time"];
            $task->required =  $doc["required"] ?? true;
            $task->portfolioStructureReq = $doc["portfolioStructureReq"];
            $task->portfolioPresentationReq = $doc["portfolioPresentationReq"];
            $task->portfolioProcedure = $doc["portfolioProcedure"];
            $task->save();
        }

        if ($doc["type"] == 2)
        {
            $task->description = $doc["description"];
            $task->portfolioProcedure = $doc["instruction"];
            $task->portfolioCriteria = $doc["control"];
            $task->required =  $doc["required"] ?? true;
            $task->time = $doc["time"];
            $task->save();
        }

        if ($ov->dpp->type->id == 1)
        {
            $ov->recount_ia();
        }

        $task = Task::where('id',$task->id)->with('subjects.objects')->with('nsis')->with('mtos')->get()->first();
        $task->name = $task->name.$task->position;
        $task->type_name = $task->task_type->short_name;
        $task->mtos = $task->mtos;
        $task->instruction = $task->portfolioProcedure;
        $task->control = $task->portfolioCriteria;
        $task->taskSteps = $task->steps;
        return $task;
    }

    public function destroy(OmVersion $ov,Task $task)
    {
        $task->nsis()->detach();
        $task->mtos()->detach();
        foreach($task->objects as $obj) {
            TaskObject::destroy($obj->id);
        }
        foreach($task->subjects as $sub) {
            TaskObject::destroy($sub->id);
        }

        $tasks = Task::where('position','>',$task->position)->where('om_version_id',$ov->id)->get();
        foreach ($tasks as $t)
        {
            $t->position = $t->position-1;
            $t->save();
        }

        Task::destroy($task->id);

        return $task->id;
    }


    public function subject_store(OmVersion $ov,Task $task,Request $request)
    {
        $result = [];
        $subject = $request->subject;
        if ($subject["type"] == 2)
        {
                 $ts = new TaskSubject;
                 $ts->task_id = $task->id;
                 $ts->subject_type_id = $subject["type"];
                 $ts->ability_id = $subject["ability"];
                 $ts->save();
                 $ts->name = $ts->ability->name;
        }
        if ( ($subject["type"] == 3) || ($subject["type"] == 4) )
        {
            $ts = new TaskSubject;
            $ts->task_id = $task->id;
            $ts->subject_type_id = $subject["type"];
            $ts->skill_id = $subject["skill"];
            $ts->save();
            $ts->name = $ts->skill->name;
            if ($subject["type"] == 4)
            {
                $ts->name = $ts->skill->name." (и входящие в навык умения)";;
            }
            //array_push($result,$ts);
        }
        $ts->objects = [];
        return json_encode($ts);
    }

    public function subject_destroy(OmVersion $ov,Task $task, TaskSubject $subject, Request $request)
    {
        foreach ($subject->objects as $object)
        {
            TaskObject::destroy($object->id);
        }
        TaskSubject::destroy($subject->id);
        return response()->json(['message'=>'success'],200);
    }

    public function object_store(OmVersion $ov,Task $task, TaskSubject $subject, Request $request)
    {
        $object = $request->object;
        $to = new TaskObject;
        $to->task_id = $task->id;
        $to->subject_id = $subject->id;
        $to->name = $object["name"];
        $to->model_answer = $object["modelAnswer"];
        $to->save();
        $to->modelAnswer = $to->model_answer;
        return $to;
    }

    public function object_update(OmVersion $ov,Task $task, TaskSubject $subject, TaskObject $object, Request $request)
    {
        $ob = $request->object;
        $object->task_id = $task->id;
        $object->subject_id = $subject->id;
        $object->name = $ob["name"];
        $object->model_answer = $ob["modelAnswer"];
        $object->save();
        $object->modelAnswer = $object->model_answer;
        return $object;
    }

    public function object_destroy(OmVersion $ov,Task $task, TaskSubject $subject, TaskObject $object, Request $request)
    {
        TaskObject::destroy($object->id);
        return response()->json(['message'=>'success'],200);
    }

    public function nsis_select(OmVersion $ov,Task $task, Request $request)
    {
        $task->nsis()->sync($request->nsis);
        return $task->nsis;
    }

    public function nsis_unselect(OmVersion $ov,Task $task, $nsi_id, Request $request)
    {
        $task->nsis()->detach($nsi_id);
    return $nsi_id;
    }

    public function mtos_select(OmVersion $ov,Task $task, Request $request)
    {
        $task->mtos()->sync($request->mtos);
        return $task->mtos;
    }

    public function mtos_unselect(OmVersion $ov,Task $task, $mto_id, Request $request)
    {
        $task->mtos()->detach($mto_id);
        return $mto_id;
    }

    public function add_base_mto_to_task(Dpp $dpp,Task $task)
    {
        $practice_mtos = Mto::where('dpp_id',$dpp->id)->where('is_base_for_practice',true)->get()->pluck('id');
        $task->mtos()->sync($practice_mtos);
    }

    public function add_base_nsis_to_task(Dpp $dpp,Task $task)
    {
        $subjects = $task->subjects;
        foreach ($subjects as $subject)
        {
            if ($subject->subject_type_id == 2)
            {
                $zun = $subject->ability;
                $nsis = $zun->nsis;
            }else if ($subject->subject_type_id > 2)
            {
                $zun = $subject->skill;
                $nsis = $zun->nsis;
            }
            foreach ($nsis as $nsi)
            {
                $task->nsis()->syncWithoutDetaching($nsi->id);
            }
        }
    }

    public function store_step(OmVersion $ov,Task $task, Request $request)
    {
        $steps_count = TaskStep::where("task_id","=", $task->id)->get()->count();
        $step = new TaskStep;
        $step->task_id = $task->id;
        $step->text = $request->input('step.text');
        $step->object = $request->input('step.object');
        $step->rightAnswer = $request->input('step.answer');
        $step->help = $request->input('step.advice');
        $step->position = $steps_count+1;
        $step->save();
        return new TaskStepResource($step);
    }

    public function destroy_step(OmVersion $ov,Task $task, TaskStep $step)
    {
        $steps = TaskStep::where('position','>',$step->position)->where('task_id',$task->id)->get();
        foreach ($steps as $s)
        {
            $s->position = $s->position-1;
            $s->save();
        }

        TaskStep::destroy($step->id);

        return $step->id;
    }

    public function update_step(OmVersion $ov,Task $task, TaskStep $step, Request $request)
    {
        $step->text = $request->input('step.text');
        $step->object = $request->input('step.object');
        $step->rightAnswer = $request->input('step.answer');
        $step->help = $request->input('step.advice');
        $step->save();
        return new TaskStepResource($step);
    }

    public function steps_reorder(OmVersion $ov,Task $task,Request $request)
    {
        $steps = $request->steps;
        $position = 1;
        foreach ($steps as $id)
        {
            $step = TaskStep::where('id',$id)->first();
            $step->position = $position;
            $step->save();
            $position++;
        }

        return TaskStepResource::collection($task->steps);
    }
}
