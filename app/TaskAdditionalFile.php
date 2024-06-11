<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TaskAdditionalFile extends Model
{
    public static function boot() {
        parent::boot();

        static::deleting(function($file) 
        { 
            Storage::deleteDirectory('task_additional_files/'.$file->id);
        });
    }
}
