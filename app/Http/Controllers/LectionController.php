<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ContentVersion;
use App\Lection;
use App\Dpp;
use App\Content;
use App\StructureSection;
use Auth;
use App\Http\Resources\LectionResource;

class LectionController extends Controller
{

    public function show(ContentVersion $ct, StructureSection $theme, $type)
    {
        //$theme = StructureSection::find($theme);
        $content = Lection::firstOrNew(['section_id' =>  $theme->id,'type' => $type,'ct_version_id' => $ct->id]);
        $content->name = $theme->name;
        $content->additional_files;
        $content->save();
        //return $content;
        return new LectionResource ($content);
    }

    public function upload(ContentVersion $ct, StructureSection $theme, $type, Request $request)
    {
        //dd("KKE");
        //dd($request->all());
        $path = $request->file('file');
        //dd($type);
        //$path->store('templates');
        //$content = Lection::firstOrNew(['section_id' =>  $theme->id,'type' => $type,'ct_version_id' => $ct->id]);
        switch ($type)
        {
            case "lec": $f_name = "Лекция"; break;
            case "pr": $f_name = "Практическое_занятие"; break;
            case "lab": $f_name = "Лабораторная_работа"; break;
        }

        $lection = Lection::firstOrNew(['section_id' =>  $theme->id,'type' => $type,'ct_version_id' => $ct->id]);
        $name = $request->file('file')->getClientOriginalName();
        $extension = $request->file('file')->getClientOriginalExtension();
        $sp = $theme->parent->position + 1;
        $tp = $theme->position + 1;
        $path = $request->file('file')->storeAs('temp/', $f_name.'.'.$extension);
        //$objReader = \PhpOffice\PhpWord\IOFactory::createReader('OOXML');
        //$source = storage_path('app/lections/'.$lection->id.'/'.$theme->parent->position.".".$theme->position."_".$type.".".$extension);
        $source = storage_path('app/temp/'. $f_name.'.'.$extension);
        //docxSettings
        //$t = new \Phpdocx\Create\CreateDocxFromTemplate($source);
        //$t->createDocx($source);
        //require_once '\Phpdocx\classes\Indexer.php';
        $indexer = new \Phpdocx\Utilities\Indexer($source);
        $output = $indexer->getOutput();
        // $phpWord = \PhpOffice\PhpWord\IOFactory::load($source);
        try
        {
            $path = $request->file('file')->storeAs('lections/'.$lection->id.'/', $f_name.'.'.$extension);
            $lection->is_loaded = true;
            $lection->superviser = 0;
            $lection->normocontroller = 0;
            $lection->superviser_id = null;
            $lection->normocontroller_id = null;
            $lection->save();
            return response()->json(['message'=>'success'],200);
        } catch(Exception $e)
        {
            return response()->json(['message'=>'forbidden'],403);
        }
        /*
        try
        {
            $a = $output["properties"]["custom"]["check"];
            if ($a == "OK")
            {
                $path = $request->file('file')->storeAs('lections/'.$lection->id.'/', $f_name.'.'.$extension);
                $lection->is_loaded = true;
                $lection->save();
                return response()->json(['message'=>'success'],200);
            }else{
                return response()->json(['message'=>'forbidden'],403);
            }
        } catch(Exception $e)
        {
            return response()->json(['message'=>'forbidden'],403);
        }
        */
    }

    public function unlink(ContentVersion $ct, StructureSection $theme, $type, Request $request)
    {

        switch ($type)
        {
            case "lec": $f_name = "Лекция"; break;
            case "pr": $f_name = "Практическое_занятие"; break;
            case "lab": $f_name = "Лабораторная_работа"; break;
        }

        //$lection =
        $lection = Lection::where(['section_id' =>  $theme->id,'type' => $type,'ct_version_id' => $ct->id])->get()->first();
        $lection->is_loaded = false;
        $lection->save();
        if (file_exists (storage_path('app/lections/'.$lection->id.'/'.$f_name.'.docm')) )
            unlink(storage_path('app/lections/'.$lection->id.'/'.$f_name.'.docm'));

        return response()->json(['message'=>'success'],200);
    }

    public function accept_toggle(Lection $lection, $type)
    {
        if ($type == 'normocontroller')
        {
            if ($lection->normocontroller == false)
            {
                $lection->normocontroller = true;
                $lection->normocontroller_id = Auth::user()->id;
            }else{
                $lection->normocontroller = false;
                $lection->normocontroller_id = null;
            }
            $lection->save();
        }

        if ($type == 'superviser')
        {
            if ($lection->superviser == false)
            {
                $lection->superviser = true;
                $lection->superviser_id = Auth::user()->id;
            }else{
                $lection->superviser = false;
                $lection->superviser_id = null;
            }
            $lection->save();
        }

        return new LectionResource ($lection);
    }

    public function add_lection(Dpp $dpp, ContentVersion $cv, Request $request)
    {
        $lections_count = Lection::where('section_id',$request->theme["id"])->get()->count();
        $lection = new Lection;
        $lection->ct_version_id = $cv->id;
        $lection->section_id = $request->theme["id"];
        $lection->name = $request->theme["name"];
        $lection->position = $lections_count+1;
        $lection->save();
        $part = new Content;
        $part->position = 0;
        $part->name = "no";
        $part->text = "<p> </p>";
        $part->lection_id = $lection->id;
        $part->save();
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
        $part->position = $parts->count();
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
        $part->symbols = $request->part_data["symbols"];
        $part->words = $request->part_data["words"];
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

    public function load_split_lections (Dpp $dpp) {
        $sections = StructureSection::where('st_version_id',$dpp->st_version_id)->where('parent_id',null)->where('name','<>','Итоговая аттестация')->orderBy('position')->get();
        foreach ($sections as $key => $section)
        {
            $lections = StructureSection::where('parent_id',$section->id)->orderBy('position')->get();
            foreach ($lections as $key_lec => $lection)
            {
                $lectionContent = Lection::where('section_id',$lection->id)->where('type','lec')->get()->first();
                $sourceFilePath = storage_path('app/lections/'.$lectionContent->id.'/Лекция.docm');
                $destinationFilePathTemp = storage_path('app/lections/'.$lectionContent->id.'/Лекция_temp.docm');

                $destinationFilePath = storage_path('app/splitted/lections/'.$dpp->id.'/Лекция '.$section->position.'.'.$lection->position.'.docm');
                if (copy($sourceFilePath, $destinationFilePathTemp)) {
                    copy($destinationFilePath, $sourceFilePath);
                }else{

                }

            }
        }
        return "OK";
    }

    public function load_split_practice (Dpp $dpp) {
        $sections = StructureSection::where('st_version_id',$dpp->st_version_id)->where('parent_id',null)->where('name','<>','Итоговая аттестация')->orderBy('position')->get();
        //Практическое_занятие
        foreach ($sections as $key => $section)
        {
            $lections = StructureSection::where('parent_id',$section->id)->orderBy('position')->get();
            foreach ($lections as $key_lec => $lection)
            {
                $lectionContent = Lection::where('section_id',$lection->id)->where('type','pr')->where('is_loaded',true)->get()->first();
                if ($lectionContent) {
                    $sourceFilePath = storage_path('app/lections/'.$lectionContent->id.'/Практическое_занятие.docm');
                    $destinationFilePathTemp = storage_path('app/lections/'.$lectionContent->id.'/Практическое_занятие_temp.docm');

                    $destinationFilePath = storage_path('app/splitted/practice/'.$dpp->id.'/Практическое_занятие_'.$section->position.'.'.$lection->position.'.docm');
                    if (copy($sourceFilePath, $destinationFilePathTemp)) {
                        copy($destinationFilePath, $sourceFilePath);
                    }else{

                    }
                }
            }
        }
        return "OK";
    }
}
