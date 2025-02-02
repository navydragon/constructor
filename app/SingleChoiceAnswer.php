<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


class SingleChoiceAnswer extends Model
{
    protected $appends = array('isCorrect');
    
    public function getIsCorrectAttribute(){
        return $this->attributes['is_right'];
    }

    // public static function boot() {
    //     parent::boot();

    //     static::deleting(function($ans) 
    //     { 
    //         if (!is_null($ans->image))
    //         {
    //             Storage::disk('public')->delete($ans->image);
    //         }
    //     });
    // }

    public function load_image($base_64)
    {
        if (!is_null($base_64))
        {
            if (!str_contains($base_64, 'base64')) { 
                $this->image = $base_64;
                $this->save();
            }else{
                $base64_image = $base_64; // your base64 encoded     
                @list($type, $file_data) = explode(';', $base64_image);
                @list(, $file_data) = explode(',', $file_data); 
                $imageName = $this->id.'.'.'png';   
                Storage::disk('public')->put('answer_files/one-answer/'.$imageName, base64_decode($file_data));
                $this->image = 'answer_files/one-answer/'.$imageName;
                $this->save(); 
            }
        }
    }
}
