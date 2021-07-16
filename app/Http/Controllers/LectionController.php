<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ContentVersion;
use App\Lection;
use App\Dpp;
use App\Content;

class LectionController extends Controller
{
    public function add_lection(Dpp $dpp, ContentVersion $cv, Request $request)
    {
        $lections_count = Lection::where('section_id',$request->theme["id"])->get()->count();
        $lection = new Lection;
        $lection->ct_version_id = $cv->id;
        $lection->section_id = $request->theme["id"];
        $lection->name = $request->theme["name"];
        $lection->position = $lections_count+1;
        $lection->save();
        return $lection;
    }

    public function get_lection(Dpp $dpp, Lection $lection)
    {
        $lection->section = $lection->section;
        $lection->knowledges = $lection->section->knowledges;
        $lection->nsis = $lection->nsis;
        foreach ($lection->knowledges as $knowledge)
        {
            $knowledge->questions = $knowledge->questions;
            foreach ($knowledge->questions as $question)
            {
                switch ($question->question_type_id) {
                case 1:
                    $question->answers = $question->single_choice_answers;
                break;
                
                case 2:
                    $question->answers = $question->multi_choice_answers;
                break;

                case 3:
                    $question->answers = $question->free_choice_answers;
                break;

                case 4:
                    $question->answers = $question->sequence_choice_answers;
                break;

                case 5:
                    $question->answers = $question->accordance_choice_answers;
                break;

                default:
                    # code...
                    break;
            }
            }
        }
        $lection->parent = $lection->section->parent;
        $lection->parts = $lection->parts;
        return $lection;
    }

    public function add_lection_part(Dpp $dpp, Lection $lection, Request $request)
    {
        $parts = Content::where('lection_id',$lection->id)->get();
        $part = new Content;
        $part->position = $parts->count()+1;
        $part->name = $request->part_data["name"];
        $part->text = "<p> </p>";
        $part->lection_id = $lection->id;
        $part->save();
        return $part;
    }

    public function update_part(Dpp $dpp, Lection $lection, Request $request)
    {
        $part = Content::find($request->part_data["id"]);
        $part->name = $request->part_data["name"];
        $part->text = $request->part_data["text"];
        $part->save();
        return $part;
    }

    public function move_up_part(Dpp $dpp,Content $part, Request $request)
    {
        $pos = $part->position;
        $changer = Content::where('lection_id','=',$part->lection_id)
        ->where('position','=',$pos-1)
        ->get()->first();
        $part->position = $changer->position;
        $changer->position = $pos;
        $part->save();
        $changer->save();
        return 'success';
    }

    public function move_down_part(Dpp $dpp,Content $part, Request $request)
    {
        $pos = $part->position;
        $changer = Content::where('lection_id','=',$part->lection_id)
        ->where('position','=',$pos+1)
        ->get()->first();
        $part->position = $changer->position;
        $changer->position = $pos;
        $part->save();
        $changer->save();
        return 'success';
    }

    public function remove_part(Dpp $dpp,Content $part, Request $request)
    {
        $below_parts = Content::where('position','>',$part->position)->where('lection_id','=',$part->lection_id)->get();
        foreach ($below_parts as $pt)
        {
            $pt->position = $pt->position - 1;
            $pt->save();
        }
        Content::destroy($part->id);
        return 'success';
    }
}
