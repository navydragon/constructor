<?php

namespace App\Http\Controllers;


use App\OmVersion;
use App\Task;
use App\TaskAdditionalFile;
use Illuminate\Http\Request;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;

class TaskAdditionalFileController extends Controller
{
    public function store(OmVersion $ov, Task $task, Request $request)
    {
        $path = $request->file('file');
        $title = $request->name;
        $additionalFilesCount = TaskAdditionalFile::where('task_id',$task->id)->get()->count();
        $af = new TaskAdditionalFile;
        $af->task_id = $task->id;
        $af->position = $additionalFilesCount+1;
        $af->name = $title;
        $af->url = " ";
        $af->save();
        
        $name = $request->file('file')->getClientOriginalName();
        $extension = $request->file('file')->getClientOriginalExtension();
        $path = $request->file('file')->storeAs('task_additional_files/'.$af->id, $name);
        $af->url = 'app/'.$path;
        $af->save();
        return $af;
    }

    public function download(Task $task, TaskAdditionalFile $af)
    {
        return response()->download(storage_path($af->url));
    }

    public function destroy(OmVersion $ov, Task $task,TaskAdditionalFile $af, Request $request)
    {
        //Storage::deleteDirectory('task_additional_files/'.$af->id);
        $af_id = $af->id;
        TaskAdditionalFile::destroy($af->id);
        return $af_id;
    }
}
