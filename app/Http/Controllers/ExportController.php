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
use App\StructureVersion;
use App\StructureSection;
use \PhpOffice\PhpWord\PhpWord;
use \PhpOffice\PhpWord\Style\Language;


class ExportController extends Controller
{
    public function export_zun(Dpp $dpp,$zv)
    {
        $phpWord = new PhpWord();
        $phpWord->getSettings()->setThemeFontLang(new Language(Language::RU_RU));

        
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
        $phpWord = new PhpWord();
        $phpWord->getSettings()->setThemeFontLang(new Language(Language::RU_RU));
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

    public function export_learning_plan(Dpp $dpp, $sv)
    {
        $phpWord = new PhpWord();
        $phpWord->getSettings()->setThemeFontLang(new Language(Language::RU_RU));
        /* Стили */
        $headStyle = array('name' => 'Times New Roman','color' => '000000', 'size' => 12, 'bold' => true);
        $boldStyle = array('name' => 'Times New Roman','color' => '000000', 'size' => 12, 'bold' => true);
        $fontStyle = array('name' => 'Times New Roman','color' => '000000', 'size' => 12, 'bold' => false);
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
        $sectionStyle = array(
            'orientation' => 'landscape',
            'marginTop' => 1133.8582677,
            'marginBottom' => 1133.8582677,
            'marginLeft' => 1700.7874016,
            'marginRight' => 850.39370079
        );
        $section = $phpWord->addSection($sectionStyle);
        /* Заголовок */
        $text='Учебный план программы «'.$dpp->name.'»';
        $cellRowSpan = array('vMerge' => 'restart', 'valign' => 'center');
        $cellRowContinue = array('vMerge' => 'continue');
        $cellColSpan2 = array('gridSpan' => 2, 'valign' => 'center');
        $cellColSpan3 = array('gridSpan' => 3, 'valign' => 'center');
        $cellHCentered = array('align' => 'center');
        $cellVCentered = array('valign' => 'center');
        $cellVerticalDirection = array('textDirection' => 'btLr','align' => 'center');
        $cellRowSpanVerticalDirection = array('textDirection' => 'btLr','vMerge' => 'restart', 'valign' => 'center');
        $normalStyle = array('name' => 'Times New Roman','color' => '000000', 'size' => 12, 'bold' => false);
        $boldStyle = array('name' => 'Times New Roman','color' => '000000', 'size' => 12, 'bold' => true);
        $section->addTitle($text, 0);
        $table = $section->addTable('standart_table');
        $table->addRow(null,array('tblHeader' => true));
        $table->addCell(4500,$cellRowSpan)->addText("Наименование разделов",$boldStyle,$cellHCentered);
        $table->addCell(3984.064,['gridSpan' => 6])->addText("Трудоемкость, ак. час",$boldStyle,$cellHCentered);
        $table->addCell(5002.846,$cellRowSpan)->addText("Планируемые результаты обучения",$boldStyle,$cellHCentered);
        $table->addRow(null,array('tblHeader' => true));
        $table->addCell(null, $cellRowContinue); // пустая
        $table->addCell(null, $cellRowSpanVerticalDirection)->addText('Итого', $boldStyle,$cellHCentered);
        $table->addCell(null,['gridSpan' => 3])->addText("Виды занятий, в т.ч.",$boldStyle,$cellHCentered);
        $styleTable = array('borderSize' => 6, 'borderColor' => '999999');
        $table->addCell(null, $cellRowSpanVerticalDirection)->addText('Самостоятельная работа', $boldStyle,$cellHCentered);
        $table->addCell(null, $cellRowSpanVerticalDirection)->addText('Итоговая аттестация', $boldStyle,$cellHCentered);
        $table->addCell(null, $cellRowContinue); // пустая
       
        $table->addRow(1775.754,array('tblHeader' => true));
        $table->addCell(null, $cellRowContinue); // пустая
        $table->addCell(null, $cellRowContinue); // пустая
        $table->addCell(842.344906,$cellVerticalDirection)->addText('лекционного типа', $boldStyle, $cellHCentered);
        $table->addCell(842.344906,$cellVerticalDirection)->addText('практического типа', $boldStyle, $cellHCentered);
        $table->addCell(842.344906,$cellVerticalDirection)->addText('лабораторная работа', $boldStyle, $cellHCentered);
        $table->addCell(null, $cellRowContinue); // пустая
        $table->addCell(null, $cellRowContinue); // пустая	
        $table->addCell(null, $cellRowContinue); // пустая
      
        $sv = StructureVersion::find($sv);
        $sections = StructureSection::with(['knowledges' => function ($query) {}])
         ->with(['themes' => function ($query) {$query->orderBy('position');}])
         ->where('parent_id','=', null)
         ->where('st_version_id','=', $sv->id)
         ->orderBy('position')
         ->get();
         foreach ($sections as $section)
         {
             $section->zuns = (object)['knowledges'=>$section->knowledges,'abilities'=>$section->abilities,'skills'=>$section->skills];
             foreach ($section->themes as $theme)
             {
                $theme->zuns = (object)['knowledges'=>$theme->knowledges,'abilities'=>$theme->abilities,'skills'=>$theme->skills];
             }
         }
        
        foreach ($sections as $section)
        {
            $table->addRow();
            $table->addCell(null)->addText($section->position." ".$section->name,$boldStyle);
            $table->addCell(null)->addText($section->total_hours,$boldStyle,$cellHCentered);
            $table->addCell(null)->addText($section->lection_hours,$boldStyle,$cellHCentered);
            $table->addCell(null)->addText($section->practice_hours,$boldStyle,$cellHCentered);
            $table->addCell(null)->addText($section->lab_hours,$boldStyle,$cellHCentered);
            $table->addCell(null)->addText($section->self_hours,$boldStyle,$cellHCentered);
            $table->addCell(null)->addText($section->attestation_hours,$boldStyle,$cellHCentered);
            
            $table_run=$table->addCell(null);
            $table_run->addText('Знания:', $boldStyle,array('spaceAfter' => 0));
            $kn_text = "";
            $knowledges = $section->knowledges;
            foreach($knowledges as $key=>$value) {
                $kn_text .=$value->name;
                if ($key != count($knowledges)-1)
                {
                    $kn_text .="; ";
                }
            }
            $table_run->addText($kn_text,$normalStyle);

            //темы
            foreach ($section->themes as $theme)
            {
                $table->addRow();
                $table->addCell(null)->addText($section->position.".".$theme->position." ".$theme->name,$normalStyle);
                $table->addCell(null)->addText($theme->total_hours,$normalStyle,$cellHCentered);
                $table->addCell(null)->addText($theme->lection_hours,$normalStyle,$cellHCentered);
                $table->addCell(null)->addText($theme->practice_hours,$normalStyle,$cellHCentered);
                $table->addCell(null)->addText($theme->lab_hours,$normalStyle,$cellHCentered);
                $table->addCell(null)->addText($theme->self_hours,$normalStyle,$cellHCentered);
                $table->addCell(null)->addText($theme->attestation_hours,$normalStyle,$cellHCentered);

                $table_run=$table->addCell(null);
                $table_run->addText('Знания:', $boldStyle,array('spaceAfter' => 0));
                $kn_text = "";
                $knowledges = $theme->knowledges;
                foreach($knowledges as $key=>$value) {
                    $kn_text .=$value->name;
                    if ($key != count($knowledges)-1)
                    {
                        $kn_text .="; ";
                    }
                }
            }
        }
        


        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        try {
            $objWriter->save(storage_path('Plan.docx'));
        } catch (Exception $e) {
        }


        return response()->download(storage_path('Plan.docx'));
    }
}
