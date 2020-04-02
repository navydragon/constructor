<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Dpp;
use App\ZunVersion;
use App\OmVersion;
use App\Competence;
use App\Skill;
use App\Ability;
use App\Knowledge;
use App\Question;


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

    public function export_om_questions(Dpp $dpp, $om)
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        /* Стили */
        $headStyle = array('name' => 'Times New Roman','color' => '000000', 'size' => 14, 'bold' => true);
        $boldStyle = array('name' => 'Times New Roman','color' => '000000', 'size' => 14, 'bold' => true);
        $fontStyle = array('name' => 'Times New Roman','color' => '000000', 'size' => 14, 'bold' => false);
        $phpWord->addTitleStyle(0, $headStyle);
        
        $tableStyle = array(
            'borderColor' => '000000',
            'borderSize'  => 1,
            'cellMargin'  => 50,
            'width'      => '100%'
        );
        $firstRowStyle = array('bgColor' => 'FFFFFF');
        $phpWord->addTableStyle('standart_table', $tableStyle, $firstRowStyle);
        

        /* Section */
        $section = $phpWord->addSection();
        /* Заголовок */
        $text='Тесты по знаниям ДПП «'.$dpp->name.'»';
        $section->addTitle($text, 0);

        $kn = 0;
        $q = 1;
        $questions = Question::where('om_version_id',$om)->orderBy('knowledge_id','asc')->get();
        $section->addText("Всего вопросов: ".count($questions), $fontStyle);
        foreach ($questions as $question)
        {
            if ($question->knowledge_id != $kn)
            {
                $kn = $question->knowledge_id;
                $section->addText("Знание: «".$question->knowledge->name."» (количество вопросов: ".$question->knowledge->questions()->count().")", $boldStyle);
            }
            $section->addText($q.") ".$question->text." (Тип вопроса: ".$question->type->name.")", $fontStyle);
            switch ($question->question_type_id) {
                case 1:
                    $section->addText("Варианты ответов:", $fontStyle);
                    foreach ($question->single_choice_answers as $answer)
                    {
                        if ($answer->is_right == true) {$prefix="(+) ";}else{$prefix="";}
                        $section->addListItem($prefix.$answer->text, 0, $fontStyle);
                    }
                break;
                
                case 2:
                    $section->addText("Варианты ответов:", $fontStyle);
                    foreach ($question->multi_choice_answers as $answer)
                    {
                        if ($answer->is_right == true) {$prefix="(+) ";}else{$prefix="";}
                        $section->addListItem($prefix.$answer->text, 0, $fontStyle);
                    }
                break;

                case 3:
                    $section->addText("Варианты правильных ответов:", $fontStyle);
                    foreach ($question->free_choice_answers as $answer)
                    {
                        $section->addListItem($answer->text, 0, $fontStyle);
                    }
                break;

                case 4:
                    $section->addText("Правильная последовательность ответов:", $fontStyle);
                    foreach ($question->sequence_choice_answers as $answer)
                    {
                        $section->addListItem($answer->text, 0, $fontStyle);
                    }
                break;

                case 5:
                    $section->addText("Таблица соответствия:", $fontStyle);
                    $table = $section->addTable('standart_table');
                    $table->addRow();
                    $table->addCell(6000)->addText("Элемент",$fontStyle);
                    $table->addCell(6000)->addText("Соответствующий элемент",$fontStyle);
                    foreach ($question->accordance_choice_answers as $answer)
                    {
                        $table->addRow();
                        $table->addCell(6000)->addText($answer->text,$fontStyle);
                        $table->addCell(6000)->addText($answer->text2,$fontStyle);
                    }
                break;

                default:
                    # code...
                    break;
            }
            $q++;
        }
        
        


        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        try {
            $objWriter->save(storage_path('Questions.docx'));
        } catch (Exception $e) {
        }


        return response()->download(storage_path('Questions.docx'));
    }
}
