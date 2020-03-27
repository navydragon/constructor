<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Dpp;
use App\ZunVersion;
use App\Competence;
use App\Skill;
use App\Ability;
use App\Knowledge;

class ExportController extends Controller
{
    public function export_zun(Dpp $dpp,$zv)
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();


        
        /* Стили */
        $headStyle = array('name' => 'Times New Roman','color' => '000000', 'size' => 14, 'bold' => true);
        $fontStyle = array('name' => 'Times New Roman','color' => '000000', 'size' => 14, 'bold' => false);
        $phpWord->addTitleStyle(0, $headStyle);
        
        /* Section */
        $section = $phpWord->addSection();
        /* Заголовок */
        $text='Результаты обучения по программе «'.$dpp->name.'»';
        $section->addTitle($text, 0);
        /* Списки */
        $competences = Competence::where('zun_version_id','=',$zv)->get();
        foreach ($competences as $competence)
        {
            $section->addListItem("Компетенция: «".$competence->name."»", 0, $fontStyle);
            $skills = Skill::where('competence_id','=',$competence->id)->orderBy('position','asc')->get();
            foreach ($skills as $skill)
            {
                $section->addListItem("Навык: «".$skill->name."»", 1, $fontStyle);
                $abilities = Ability::where('skill_id','=',$skill->id)->orderBy('position','asc')->get();
                foreach ($abilities as $ability)
                {
                    $section->addListItem("Умение: «".$ability->name."»", 2, $fontStyle);
                    $knowledges = Knowledge::where('ability_id','=',$ability->id)->orderBy('position','asc')->get();
                    foreach ($knowledges as $knowledge)
                    {
                        $section->addListItem("Знание: «".$knowledge->name."»", 3, $fontStyle);
                    }
                }
            }
            $abilities = Ability::where('competence_id','=',$competence->id)->orderBy('position','asc')->get();
            foreach ($abilities as $ability)
            {
                $section->addListItem("Умение: «".$ability->name."»", 1, $fontStyle);
                $knowledges = Knowledge::where('ability_id','=',$ability->id)->orderBy('position','asc')->get();
                foreach ($knowledges as $knowledge)
                {
                    $section->addListItem("Знание: «".$knowledge->name."»", 2, $fontStyle);
                }
            }
        }
        $skills = Skill::where('competence_id','=',null)->where('zun_version_id','=',$zv)->orderBy('position','asc')->get();
        foreach ($skills as $skill)
        {
            $section->addListItem("Навык: «".$skill->name."»", 0, $fontStyle);
            $abilities = Ability::where('skill_id','=',$skill->id)->get();
            foreach ($abilities as $ability)
            {
                $section->addListItem("Умение: «".$ability->name."»", 1, $fontStyle);
                $knowledges = Knowledge::where('ability_id','=',$ability->id)->get();
                foreach ($knowledges as $knowledge)
                {
                    $section->addListItem("Знание: «".$knowledge->name."»", 2, $fontStyle);
                }
            }
        }
        $abilities = Ability::where('competence_id','=',null)->where('skill_id','=',null)->where('zun_version_id','=',$zv)->orderBy('position','asc')->get();
        foreach ($abilities as $ability)
        {
            $section->addListItem("Умение: «".$ability->name."»", 0, $fontStyle);
            $knowledges = Knowledge::where('ability_id','=',$ability->id)->get();
            foreach ($knowledges as $knowledge)
            {
                $section->addListItem("Знание: «".$knowledge->name."»", 1, $fontStyle);
            }
        }
        $th_knowledges = Knowledge::where('zun_version_id',$zv)->where('is_through',true)->orderBy('position','asc')->get();
        $section->addListItem("Сквозные знания:", 0, $fontStyle);
        foreach ($th_knowledges as $knowledge)
        {
            $section->addListItem("Знание: «".$knowledge->name."»", 1, $fontStyle);
        }
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        try {
            $objWriter->save(storage_path('Zuns.docx'));
        } catch (Exception $e) {
        }


        return response()->download(storage_path('Zuns.docx'));
    }
}
