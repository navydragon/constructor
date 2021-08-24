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
use App\Mto;
Use App\MtoType;
use App\StructureVersion;
use App\StructureSection;
use App\IshVersion;
use Carbon\Carbon;
use App\Content;
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

    public function export_dpp(Dpp $dpp)
    {
        $phpWord = new PhpWord();
        $phpWord->getSettings()->setThemeFontLang(new Language(Language::RU_RU));
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(14);
        $phpWord->getSettings()->setUpdateFields(true);
        /* Стили */
        $headFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 14, 'bold' => true);
        $boldFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 14, 'bold' => true);
        $normalFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 14, 'bold' => false);
        $tableLittleFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 10, 'bold' => false);
        $tableNormalFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 12, 'bold' => false);
        $tableBoldFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 12, 'bold' => true);
        $redFont = array('name' => 'Times New Roman','color' => 'red', 'size' => 14, 'bold' => false);
        $titleParagraph = array('alignment' => 'both','lineHeight' => 1.5,'spaceAfter' => 0,'indentation'=> ['firstLine' => 708.661417323]);        
        $annotationTitleParagraph = array('alignment' => 'both','lineHeight' => 1.5,'spaceAfter' => 0,'spaceBefore' => 12, 'indentation'=> ['firstLine' => 708.661417323]);        
        $centerParagraph = array('alignment' => 'center','lineHeight' => 1.5,'spaceAfter' => 0);
        $rightParagraph = array('alignment' => 'right','lineHeight' => 1.5,'spaceAfter' => 0);
        $normalParagraph = array('alignment' => 'both','lineHeight' => 1.5,'spaceAfter' => 0);
        $tableNameParagraph = array('alignment' => 'both','lineHeight' => 1,'spaceAfter' => 0);
        $indentParagraph = array('alignment' => 'both','lineHeight' => 1.5,'spaceAfter' => 0,'indentation'=> ['firstLine' => 708.661417323]);
        $normalLineH1Paragraph = array('alignment' => 'both','lineHeight' => 1,'spaceAfter' => 0);
        $normalLineH1CenterParagraph = array('alignment' => 'center','lineHeight' => 1,'spaceAfter' => 0);
        $tableFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 11, 'bold' => false);
        $phpWord->addTitleStyle(0, $headFont,$titleParagraph);
        $phpWord->addTitleStyle(1, $headFont,$titleParagraph);
        $phpWord->addTitleStyle(2, $headFont,$titleParagraph);

        $phpWord->addNumberingStyle(
            'multilevel_line',
            array(
            'type' => 'multilevel',
            'levels' => array(
            array('format' => 'bullet', 'text' => '–'),
            array('format' => 'bullet', 'text' => '–', 'left' => 720, 'hanging' => 360, 'tabPos' => 720),
            )));

        $tableStyle = array( 'borderColor' => '000000', 'borderSize'  => 1, 'cellMarginLeft' => 107.716535433,'cellMarginRight' => 107.716535433, 'cellMarginTop'  => 0,'cellMarginBottom'  => 0,'width'=> '100%');
        $tableWOBordersStyle = array('cellMargin'  => 50,'width'=> '100%');
        
        $firstRowStyle = array();
        $phpWord->addTableStyle('standart_table', $tableStyle);
        $phpWord->addTableStyle('wo_borders_table', $tableWOBordersStyle, $firstRowStyle);
        
        $cellRowSpan = array('vMerge' => 'restart', 'valign' => 'center');
        $cellRowContinue = array('vMerge' => 'continue');
        $cellColSpan2 = array('gridSpan' => 2, 'valign' => 'center');
        $cellColSpan3 = array('gridSpan' => 3, 'valign' => 'center');
        $cellHCentered = array('align' => 'center');
        $cellHCenteredNoSpace = array('align' => 'center','spaceAfter' => 0);
        $cellNoSpace = array('spaceAfter' => 0,'lineHeight' => 1);
        $cellAllCentered = array('align' => 'center', 'valign' => 'center','spaceAfter' => 0);
        $cellVCentered = array('valign' => 'center');
        $cellVerticalDirection = array('textDirection' => 'btLr','align' => 'center');
        $cellRowSpanVerticalDirection = array('textDirection' => 'btLr','vMerge' => 'restart', 'valign' => 'center');

        /* Section */
        $sectionStyle = array(
            'marginTop' => 1133.8582677,
            'marginBottom' => 1133.8582677,
            'marginLeft' => 1700.7874016,
            'marginRight' => 850.39370079,
            'pageNumberingStart' => 2,
        );
        $titleSectionStyle = array(
            'marginTop' => 1133.8582677,
            'marginBottom' => 2000,
            'marginLeft' => 1700.7874016,
            'marginRight' => 850.39370079,
            'footerHeight'=>1134
        );

        

        $section = $phpWord->addSection($titleSectionStyle);
        /* ТИТУЛ */

        $section->addText('МИНИСТЕРСТВО ТРАНСПОРТА РОССИЙСКОЙ ФЕДЕРАЦИИ',$boldFont,$normalLineH1CenterParagraph);
        $section->addText('',$boldFont,$centerParagraph);
        $section->addText('ФЕДЕРАЛЬНОЕ АВТОНОМНОЕ УЧРЕЖДЕНИЕ',$boldFont,$normalLineH1CenterParagraph);
        $section->addText('«РОССИЙСКИЙ ДОРОЖНЫЙ НАУЧНО-ИССЛЕДОВАТЕЛЬСКИЙ ИНСТИТУТ» (ФАУ «РОСДОРНИИ»)',$boldFont,$normalLineH1CenterParagraph);
        $section->addText('',$boldFont,$normalLineH1CenterParagraph);
        $section->addText('',$boldFont,$normalLineH1CenterParagraph);
        $table = $section->addTable('wo_borders_table');
        $table->addRow();
        $table->addCell(4592.125984252)->addText("");
        $cell = $table->addCell(4762.204724409);
        $cell->addText("УТВЕРЖДАЮ",$normalFont,$normalLineH1Paragraph);
        $cell->addText("Генеральный директор",$normalFont,$normalLineH1Paragraph);
        $cell->addText("ФАУ «РОСДОРНИИ»",$normalFont,$normalLineH1Paragraph);
        $cell->addText("",$normalFont,$normalLineH1Paragraph);
        $cell->addText("_______________ С.Ю. Набоко",$normalFont,$normalLineH1Paragraph);
        $cell->addText("М.П.",$normalFont,$normalLineH1Paragraph);
        $cell->addText("«____» _________________ 20__ г.",$normalFont,$normalLineH1Paragraph);
        $section->addText(' ',$boldFont,$centerParagraph);
        $section->addText(' ',$boldFont,$centerParagraph);
        $section->addText(' ',$boldFont,$centerParagraph);
        $section->addText('ПРИМЕРНАЯ',$boldFont,$normalLineH1CenterParagraph);
        $section->addText('ДОПОЛНИТЕЛЬНАЯ ПРОФЕССИОНАЛЬНАЯ ПРОГРАММА –',$boldFont,$normalLineH1CenterParagraph);
        $section->addText('ПРОГРАММА ПОВЫШЕНИЯ КВАЛИФИКАЦИИ',$boldFont,$normalLineH1CenterParagraph);
        $section->addText('«'.mb_strtoupper($dpp->name).'»',$normalFont,$normalLineH1CenterParagraph);
        // $section->addText('по направлению подготовки 38.03.01 «Экономика»',$redFont,$centerParagraph);
        // $section->addText('по специальности 38.02.06 «Финансы»',$redFont,$centerParagraph);
        $len = 5;
        for ($i = 0; $i < $len; $i++)
        {
            $section->addText(' ',$boldFont,$centerParagraph);
        }
        
        $footer = $section->addFooter();
        $footer->firstPage();
        $footer->addText('Москва '.date ( 'Y' ),$boldFont,$centerParagraph);
        //$section->addPageBreak();

        $section = $phpWord->addSection($sectionStyle);
        /* АННОТАЦИЯ */
        $section->addText('АННОТАЦИЯ',$boldFont,$normalLineH1CenterParagraph);
        $section->addText('',$normalFont,$normalParagraph);
        $section->addText($dpp->name,$boldFont,$indentParagraph);
        $section->addText('Описание программы:',$boldFont,$annotationTitleParagraph);
        $section->addText('Требования к обучающимся. Эта программа для вас, если вы:',$boldFont,$annotationTitleParagraph);
        $section->addText('Цели и задачи освоения:',$boldFont,$annotationTitleParagraph);
        $section->addText('Результаты и перспективы освоения:',$boldFont,$annotationTitleParagraph);
        $section->addText('Форма и технология обучения:',$boldFont,$annotationTitleParagraph);
        $section->addText('Количество часов, отведенных на изучение дисциплины:',$boldFont,$annotationTitleParagraph);
        $section->addText('Срок освоения:',$boldFont,$annotationTitleParagraph);
        $section->addText('Форма аттестации:',$boldFont,$annotationTitleParagraph);
        
        $section->addPageBreak();
        /* Разработчики */
        $section->addText('СПИСОК РАЗРАБОТЧИКОВ',$boldFont,$centerParagraph);
        $table = $section->addTable('wo_borders_table');
        $cellBottomBorder = array('borderBottomSize' => 1,'borderBottomColor' => '000000');

        for ($i = 0; $i < 10; $i++)
        {
            $table->addRow();
            $cell=$table->addCell(3123.779527559,$cellBottomBorder);
            $cell=$table->addCell(272.125984252,);
            $cell=$table->addCell(1094.173228346,$cellBottomBorder);
            $cell=$table->addCell(272.125984252);
            $cell=$table->addCell(4773.543307087,$cellBottomBorder);
            $table->addRow();
            $cell=$table->addCell(3123.779527559)->addText('ученое звание, ученая степень',$tableLittleFont,$normalLineH1CenterParagraph);
            $cell=$table->addCell(272.125984252);
            $cell=$table->addCell(1094.173228346)->addText('подпись',$tableLittleFont,$normalLineH1CenterParagraph);
            $cell=$table->addCell(272.125984252);
            $cell=$table->addCell(4773.543307087)->addText('ФИО',$tableLittleFont,$normalLineH1CenterParagraph);
        }

        $section->addPageBreak();

        $section = $phpWord->addSection($sectionStyle);
        $footer = $section->addFooter();
        $footer->addPreserveText('{PAGE}',$tableNormalFont,$rightParagraph);
        /* Содержание */
        $section->addText('Содержание',$boldFont,$centerParagraph);
        $tocStyle = array ('tabLeader' => 'dot');
        $section->addTOC($normalFont, $tocStyle, 1, 2);
        $section->addPageBreak();
        /* 1.1.1 */ 
        $iv = IshVersion::find($dpp->ish_version_id);
        $section->addTitle('1 Общая характеристика программы',1);
        $section->addTitle('1.1 Общие положения',2);
        $section->addText('1.1.1 Нормативные правовые основания разработки',$headFont,$titleParagraph);
        $section->addText('Нормативные правовые основания для разработки примерной дополнительной профессиональной программы – программы повышения квалификации «'.$dpp->name.'» (далее – программа) составляют:',$normalFont,$indentParagraph);
        $section->addListItem('Федеральный закон от 29 декабря 2012 г. № 273-ФЗ «Об образовании в Российской Федерации»;', 0, $normalFont,'multilevel_line' ,$indentParagraph);
        $section->addListItem('Федеральный закон от 03 июля 2016 г. № 238-ФЗ «О независимой оценке квалификации»;', 0, $normalFont,'multilevel_line' ,$indentParagraph);
        $section->addListItem('Постановление Правительства Российской Федерации от 22 января 2013 г. № 23 «О Правилах разработки, утверждения и применения профессиональных стандартов»;', 0, $normalFont,'multilevel_line' ,$indentParagraph);
        $section->addListItem('приказ Минтруда России от 12 апреля 2013 г. № 148н «Об утверждении уровней квалификаций в целях разработки проектов профессиональных стандартов»;', 0, $normalFont,'multilevel_line' ,$indentParagraph);
        $section->addListItem('приказ Минобрнауки России от 01 июля 2013 г. № 499 «Об утверждении Порядка организации и осуществления образовательной деятельности по дополнительным профессиональным программам»;', 0, $normalFont,'multilevel_line' ,$indentParagraph);
        $section->addListItem('приказ Минтруда России от 01 ноября 2016 г. № 601н «Об утверждении Положения о разработке оценочных средств для проведения независимой оценки квалификации».', 0, $normalFont,'multilevel_line' ,$indentParagraph);		
        /*ПРОФСТАНДАРТЫ */
        $pss = $iv->prof_standarts;
        if (count($pss) > 0)
        {
            $textrun = $section->addTextRun($indentParagraph);
            if (count($pss) == 1) {
                $textrun->addText('Программа разработана на основе профессионального стандарта ',$normalFont);
                $value = $pss[0];
                $od = Carbon::parse($value->orderDate)->format('d.m.Y');
                $rd = Carbon::parse($value->registrationDate)->format('d.m.Y');
                
                $textrun->addText($value->nameCode." ".$value->nameText." (утвержден приказом Минтруда России от ".$od." г. №".$value->orderNumber."н, зарегистрирован Министерством юстиции Российской Федерации ".$rd." г., регистрационный № ".$value->registrationNumber.").",$normalFont);
            }else
            {
                $textrun->addText('Программа разработана на основе профессиональных стандартов:',$normalFont);
                foreach($pss as $key=>$value) {
                    $od = Carbon::parse($value->orderDate)->format('d.m.Y');
                    $rd = Carbon::parse($value->registrationDate)->format('d.m.Y');
                    if ($key != count($pss)-1)
                    {
                        $section->addListItem($value->nameCode." ".$value->nameText." (утвержден приказом Минтруда России от ".$od." г. №".$value->orderNumber."н, зарегистрирован Министерством юстиции Российской Федерации ".$rd." г., регистрационный № ".$value->registrationNumber.");", 0, $normalFont,'multilevel_line' ,$indentParagraph);		
                    }else{
                        $section->addListItem($value->nameCode." ".$value->nameText." (утвержден приказом Минтруда России от ".$od." г. №".$value->orderNumber."н, зарегистрирован Министерством юстиции Российской Федерации ".$rd." г., регистрационный № ".$value->registrationNumber.").", 0, $normalFont,'multilevel_line' ,$indentParagraph);		
                    }
                }    
            }
            
        }

        /* ФГОСы */
        $spos = $iv->fgoses()->where((function ($q) {$q->where("fgos_level_id",1)->orWhere("fgos_level_id",5);}))->get();
        if (count($spos) > 0)
        {
            $textrun = $section->addTextRun($indentParagraph);
            if (count($spos) == 1) {
                $textrun->addText('Программа разработана на основе требований федерального государственного образовательного стандарта среднего профессионального образования по ',$normalFont);
                $value = $spos[0];
                if ($value->fgos_level_id == 1) {$word = 'профессии';}else{$word = 'специальности';}
                $textrun->addText($word." ",$normalFont);
                $textrun->addText($value->code." «".$value->name."»",$normalFont);
                $textrun->addText(", к результатам освоения образовательных программ.");
            }else{
                $textrun->addText('Программа разработана на основе требований федеральных государственных образовательных стандартов среднего профессионального образования к результатам освоения образовательных программ по:',$normalFont);
                foreach($spos as $key=>$value) {
                    if ($value->fgos_level_id == 1) {$word = 'профессии';}else{$word = 'специальности';}
                    if ($key != count($spos)-1)
                    {
                        $section->addListItem($word." ".$value->code." «".$value->name."»;",0, $normalFont,'multilevel_line', $indentParagraph);		
                    }else{
                        $section->addListItem($word." ".$value->code." «".$value->name."».",0, $normalFont,'multilevel_line', $indentParagraph);		
                    }
                }    
            }
        }
        $vpos = $iv->fgoses()->where((function ($q) {$q->where("fgos_level_id","<>",1)->where("fgos_level_id","<>",5);}))->get();
        if (count($vpos) > 0)
        {
            $textrun = $section->addTextRun($indentParagraph);
            if (count($vpos) == 1) {
                $textrun->addText('Программа разработана на основе требований федерального государственного образовательного стандарта высшего профессионального образования по ',$normalFont);
                $value = $vpos[0];
                if ($value->fgos_level_id == 3) {$word = 'специальности';}else{$word = 'направлению подготовки';}
                $textrun->addText($word." ",$normalFont);
                $textrun->addText($value->code." «".$value->name."»",$normalFont);
                if ($value->fgos_level_id == 2) {$word = ' (уровень бакалавриата)';}
                if ($value->fgos_level_id == 3) {$word = ' (уровень специалитета)';}
                if ($value->fgos_level_id == 4) {$word = ' (уровень магистратуры)';}
                if ($value->fgos_level_id == 6) {$word = ' (уровень аспирантуры)';}
                $textrun->addText($word);
                $textrun->addText(", к результатам освоения образовательных программ.");
            }else{
                $textrun->addText('Программа разработана на основе требований федеральных государственных образовательных стандартов высшего профессионального образования к результатам освоения образовательных программ по:',$normalFont);
                foreach($vpos as $key=>$value) {
                    if ($value->fgos_level_id == 3) {$word = 'специальности';}else{$word = 'направлению подготовки';}
                    if ($value->fgos_level_id == 2) {$word2 = ' (уровень бакалавриата)';}
                    if ($value->fgos_level_id == 3) {$word2 = ' (уровень специалитета)';}
                    if ($value->fgos_level_id == 4) {$word2 = ' (уровень магистратуры)';}
                    if ($value->fgos_level_id == 6) {$word2 = ' (уровень аспирантуры)';}
                    if ($key != count($spos)-1)
                    {
                        $section->addListItem($word." ".$value->code." «".$value->name."» ".$word2.";",0, $normalFont,'multilevel_line', $indentParagraph);		
                    }else{
                        $section->addListItem($word." ".$value->code." «".$value->name."» ".$word2.".",0, $normalFont,'multilevel_line', $indentParagraph);		
                    }
                }    
            }
        }
        
        
        /* 1.1.2 */
        $pl = $iv->prof_levels;
        $section->addText('1.1.2 Требования к обучающимся',$headFont,$titleParagraph);
        $section->addText('Требования к уровню профессионального образования:',$normalFont,$indentParagraph);
        foreach ($pl as $elem)
        {
            $section->addListItem($elem->name, 0, $normalFont,'multilevel_line' ,$indentParagraph);
        }
        $textrun = $section->addTextRun($indentParagraph);
        $textrun->addText('Требование к квалификации: ',$boldFont);
        $textrun->addText($iv->req_user_kval, $normalFont);
        
        /* 1.1.3 */
        $section->addText('1.1.3 Форма обучения',$headFont,$titleParagraph);
        $section->addText('Повышение квалификации может проводиться по выбору образовательной организации в соответствии с учебным планом в очной, очно-заочной или заочной формах обучения с применением дистанционных образовательных технологий и (или) электронного обучения',$normalFont,$indentParagraph);
        /* 1.1.4 */
        $section->addText('1.1.4 Трудоемкость освоения',$headFont,$titleParagraph);
        $section->addText($dpp->total_hours.' ак. ч., включая все виды контактной и самостоятельной работы обучающегося.',$normalFont,$indentParagraph);

        /* 1.1.5 */
        $hours = $dpp->total_hours;
        $o_days = ceil($hours / 8);
        $z_days = ceil($hours / 4);
        $section->addText('1.1.5 Срок освоения',$headFont,$titleParagraph);
        switch ($o_days % 10)
        {
            case 0: $o_phrase = "календарных дней";break; case 1: $o_phrase = "календарный день";break;
            case 2: case 3: case 4: $o_phrase = "календарных дня"; break;
            case 5: case 6: case 7: case 8: case 9: $o_phrase = "календарных дней"; break;
        }
        switch ($z_days % 10)
        {
            case 0: $z_phrase = "календарных дней";break; case 1: $z_phrase = "календарный день";break;
            case 2: case 3: case 4: $z_phrase = "календарных дня"; break;
            case 5: case 6: case 7: case 8: case 9: $z_phrase = "календарных дней"; break;
        }
        $section->addText($o_days.' '.$o_phrase.' для очной формы обучения или '.$z_days.' '.$z_phrase.' для очно-заочной и заочной форм обучения с применением дистанционных образовательных технологий.',$normalFont,$indentParagraph);
        /* 1.2 */
        $section->addTitle('1.2 Цель и задачи освоения',2);
        /* 1.2.1 */
        $section->addText('1.2.1 Цель освоения',$headFont,$titleParagraph);
        $section->addText('Целью освоения программы являются совершенствование профессиональных компетенций работников дорожного хозяйства, необходимых для решения задач профессиональной деятельности при формировании сметной стоимости работ по капитальному ремонту автомобильных дорог, повышение профессионального уровня в рамках имеющейся квалификации в области профессиональной деятельности.',$normalFont,$indentParagraph);
        
        /* 1.2.2 */
        $section->addText('1.2.2 Задачи освоения',$headFont,$titleParagraph);
        $section->addText('Задачами освоения программы являются:',$normalFont,$indentParagraph);
        $section->addListItem('приобретение обучающимися знаний, умений и навыков в соответствии с учебным планом и календарным графиком учебного процесса;', 0, $normalFont,'multilevel_line' ,$indentParagraph);
        $section->addListItem('оценка достижений обучающимися планируемых результатов обучения.', 0, $normalFont,'multilevel_line' ,$indentParagraph);

         /* 1.3 */
        $section->addTitle('1.3 Планируемые результаты освоения, соотнесенные с планируемыми результатами обучения',2);
        $section->addText('Таблица 1 – Планируемые результаты освоения:',$normalFont,$normalParagraph);
        $table = $section->addTable('standart_table');
        $table->addRow(null,array('tblHeader' => true));
        $cell=$table->addCell(1985)->addText('Планируемые результаты освоения',$tableBoldFont,$normalLineH1CenterParagraph);
        $cell=$table->addCell(7477)->addText('Планируемые результаты обучения',$tableBoldFont,$normalLineH1CenterParagraph);
        $zv = $dpp->zun_version_id;
        $competences = Competence::where('zun_version_id','=',$zv)->get();
        foreach ($competences as $competence)
        {
            $table->addRow();
            $cell=$table->addCell(1985)->addText($competence->name,$tableNormalFont,$cellNoSpace);
            $cell=$table->addCell(7477);
            $cell->addText('Знания:',$tableBoldFont,array('spaceAfter' => 0));
            $kn_text = "";
            $knowledges = Knowledge::where('zun_version_id','=',$zv)->get();
            foreach($knowledges as $key=>$value) {
                $kn_text .=$value->name;
                if ($key != count($knowledges)-1)
                {
                    $kn_text .="; ";
                }
            }
            $cell->addText($kn_text,$tableNormalFont,array('spaceAfter' => 0));
            $cell->addText('Умения:',$tableBoldFont,array('spaceAfter' => 0));
            $ab_text = "";
            $abilities = Ability::where('zun_version_id','=',$zv)->get();
            foreach($abilities as $key=>$value) {
                $ab_text .=$value->name;
                if ($key != count($abilities)-1)
                {
                    $ab_text .="; ";
                }
            }
            $cell->addText($ab_text,$tableNormalFont,array('spaceAfter' => 0));

            $cell->addText('Навыки:',$tableBoldFont,array('spaceAfter' => 0));
            $sk_text = "";
            $skills = Skill::where('zun_version_id','=',$zv)->get();
            foreach($skills as $key=>$value) {
                $sk_text .=$value->name;
                if ($key != count($abilities)-1)
                {
                    $sk_text .="; ";
                }
            }
            $cell->addText($sk_text,$tableNormalFont,array('spaceAfter' => 0));
        }
        
        /* 1.4 */
        $section->addTitle('1.4 Учебный план ',2);
        $section->addText('Таблица 2 – Учебный план',$normalFont,$normalParagraph);
       

        $table = $section->addTable('standart_table');
        $table->addRow(null,array('tblHeader' => true));
        $table->addCell(null,$cellRowSpan)->addText("Наименование разделов",$tableBoldFont,$cellHCentered);
        $table->addCell(4200,['gridSpan' => 6])->addText("Трудоемкость, ак. час",$tableBoldFont,$cellHCenteredNoSpace);
        $table->addCell(null,$cellRowSpan)->addText("Планируемые результаты обучения",$tableBoldFont,$cellHCentered);
        $table->addRow(null,array('tblHeader' => true));
        $table->addCell(null, $cellRowContinue); // пустая
        $table->addCell(600, $cellRowSpanVerticalDirection)->addText('Итого', $tableBoldFont,$cellAllCentered);
        $table->addCell(2250,['gridSpan' => 3])->addText("Виды занятий, в т.ч.",$tableBoldFont,$cellHCenteredNoSpace);
        //$styleTable = array('borderSize' => 6, 'borderColor' => '999999');
        $table->addCell(750, $cellRowSpanVerticalDirection)->addText('Самостоятельная работа', $tableBoldFont,$cellAllCentered);
        $table->addCell(600, $cellRowSpanVerticalDirection)->addText('Итоговая аттестация', $tableBoldFont,$cellAllCentered);
        $table->addCell(null, $cellRowContinue); // пустая
       
        $table->addRow(1800,array('tblHeader' => true));
        $table->addCell(null, $cellRowContinue); // пустая
        $table->addCell(null, $cellRowContinue); // пустая
        $table->addCell(750,$cellVerticalDirection)->addText('лекционного типа', $tableBoldFont, $cellAllCentered);
        $table->addCell(750,$cellVerticalDirection)->addText('практического типа', $tableBoldFont, $cellAllCentered);
        $table->addCell(750,$cellVerticalDirection)->addText('лабораторная работа', $tableBoldFont, $cellAllCentered);
        $table->addCell(null, $cellRowContinue); // пустая
        $table->addCell(null, $cellRowContinue); // пустая	
        $table->addCell(null, $cellRowContinue); // пустая
        $sv = StructureVersion::find($dpp->st_version_id);
        $sections = StructureSection::with(['knowledges' => function ($query) {}])
         ->with(['themes' => function ($query) {$query->orderBy('position');}])
         ->where('parent_id','=', null)
         ->where('st_version_id','=', $sv->id)
         ->orderBy('position')
         ->get();
         foreach ($sections as $sect)
         {
             $section->zuns = (object)['knowledges'=>$sect->knowledges,'abilities'=>$sect->abilities,'skills'=>$sect->skills];
             foreach ($sect->themes as $theme)
             {
                $theme->zuns = (object)['knowledges'=>$theme->knowledges,'abilities'=>$theme->abilities,'skills'=>$theme->skills];
             }
         }
        
        foreach ($sections as $sect)
        {
            $table->addRow();
            $table->addCell(null)->addText($sect->position." ".$sect->name,$tableBoldFont,$cellNoSpace);
            $table->addCell(null)->addText($sect->total_hours,$tableBoldFont,$cellNoSpace);
            $table->addCell(737)->addText($sect->lection_hours,$tableBoldFont,$cellNoSpace);
            $table->addCell(737)->addText($sect->practice_hours,$tableBoldFont,$cellNoSpace);
            $table->addCell(737)->addText($sect->lab_hours,$tableBoldFont,$cellNoSpace);
            $table->addCell(null)->addText($sect->self_hours,$tableBoldFont,$cellNoSpace);
            $table->addCell(null)->addText($sect->attestation_hours,$tableBoldFont,$cellNoSpace);
            
            $table_run=$table->addCell(null);
            $table_run->addText('Знания:', $tableBoldFont,array('spaceAfter' => 0));
            $kn_text = "";
            $knowledges = $sect->knowledges;
            foreach($knowledges as $key=>$value) {
                $kn_text .=$value->name;
                if ($key != count($knowledges)-1)
                {
                    $kn_text .="; ";
                }
            }
            $table_run->addText($kn_text,$tableNormalFont);

            //темы
            foreach ($sect->themes as $theme)
            {
                $table->addRow();
                $table->addCell(null)->addText($sect->position.".".$theme->position." ".$theme->name,$tableNormalFont,$cellNoSpace);
                $table->addCell(null)->addText($theme->total_hours,$tableNormalFont,$cellHCentered,$cellNoSpace);
                $table->addCell(null)->addText($theme->lection_hours,$tableNormalFont,$cellHCentered,$cellNoSpace);
                $table->addCell(null)->addText($theme->practice_hours,$tableNormalFont,$cellHCentered,$cellNoSpace);
                $table->addCell(null)->addText($theme->lab_hours,$tableNormalFont,$cellHCentered,$cellNoSpace);
                $table->addCell(null)->addText($theme->self_hours,$tableNormalFont,$cellHCentered,$cellNoSpace);
                $table->addCell(null)->addText($theme->attestation_hours,$tableNormalFont,$cellHCentered,$cellNoSpace);

                $table_run=$table->addCell(null);
                $table_run->addText('Знания:', $tableBoldFont,array('spaceAfter' => 0));
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

         /* 1.5 */
         $o_width = 4500 / $o_days;
         $z_width = 4500 / $z_days;
         $section->addText('',$headFont,$titleParagraph);
         $section->addTitle('1.5 Календарный учебный график',2);
         /* ОЧНЫЕ ДНИ */
         $section->addText('Таблица 3 – Календарный учебный график для очной формы обучения',$normalFont,$tableNameParagraph);
         $table = $section->addTable('standart_table');
         $table->addRow(null,array('tblHeader' => true));
         $table->addCell(null,$cellRowSpan)->addText("Наименование разделов",$tableBoldFont,$cellHCentered);
         $table->addCell(null,['gridSpan' => $o_days])->addText("Количество академических часов по дням",$tableBoldFont,$cellHCenteredNoSpace);
         $table->addCell(1150,$cellRowSpan)->addText("ИТОГО",$tableBoldFont,$cellHCentered);
         $table->addRow(null,array('tblHeader' => true));
         $table->addCell(null, $cellRowContinue); // пустая
         for ($i = 1; $i <= $o_days; $i++)
         {
            $table->addCell($o_width)->addText('Д'.$i, $tableBoldFont,$cellAllCentered);
         }
         $table->addCell(null, $cellRowContinue); // пустая 
        
         $sections = StructureSection::where('parent_id','=', null)->where('st_version_id','=', $sv->id)->orderBy('position')->get();
         
         $busy_cells = 0;
         $free_hours = 8;
         $days_time = [0];
         foreach ($sections as $sect)
         {
            $table->addRow(null);
            $table->addCell(null)->addText($sect->position." ".$sect->name,$tableNormalFont,$cellNoSpace);
            for ($i = 0; $i < $busy_cells; $i++)
            {
                $table->addCell($o_width);
            }
            if ($sect->total_hours < $free_hours)
            { 
                $table->addCell($o_width)->addText($sect->total_hours,$tableNormalFont,$cellHCenteredNoSpace);
                $free_hours -= $sect->total_hours;
                $skip_cells = $o_days - $busy_cells-1;
                $days_time[$busy_cells]+= $sect->total_hours;
            }
            else if ($sect->total_hours == $free_hours)
            {
                $table->addCell($o_width)->addText($sect->total_hours,$tableNormalFont,$cellHCenteredNoSpace);
                $days_time[$busy_cells]+= $sect->total_hours;
                $days_time[$busy_cells+1] = 0;
                $busy_cells++;
                $free_hours = 8;
                $skip_cells = $o_days - $busy_cells;
            }
            else if ($sect->total_hours > $free_hours)
            {
                $to_serve = $sect->total_hours;
                while ($to_serve > 0)
                {
                    if ($to_serve >= $free_hours)
                    {
                        $table->addCell($o_width)->addText($free_hours,$tableNormalFont,$cellHCenteredNoSpace);
                        if ($to_serve > $free_hours) {$skip_cells--;}
                        $to_serve -= $free_hours;
                        $days_time[$busy_cells]+= $free_hours;
                        $days_time[$busy_cells+1] = 0;
                        $busy_cells++;
                        $free_hours = 8;
                    }else{
                        $table->addCell($o_width)->addText($to_serve,$tableNormalFont,$cellHCenteredNoSpace);
                        $free_hours -= $to_serve;
                        $days_time[$busy_cells]+= $to_serve;
                        $to_serve = 0;
                    }
                }
               // $skip_cells = $o_days - $busy_cells-1;
            }
            
            for ($i = 0; $i<$skip_cells; $i++)
            {
                $table->addCell($o_width);
            }
            $table->addCell($o_width)->addText($sect->total_hours,$tableBoldFont,$cellHCenteredNoSpace);
         }
         $table->addRow(null);
         $table->addCell()->addText('Всего ак. часов',$tableBoldFont,$cellHCenteredNoSpace);
         if ($days_time[count($days_time)-1] == 0) {array_pop($days_time);}
         foreach ($days_time as $key=>$value)
         {
            $table->addCell($o_width)->addText($value,$tableBoldFont,$cellHCenteredNoSpace);
         }
         $table->addCell()->addText(array_sum($days_time),$tableBoldFont,$cellHCenteredNoSpace);
         
         $section->addText('',$headFont,$titleParagraph);
         /* ОЧНО-ЗАОЧНЫЕ ДНИ */
         $section->addText('Таблица 4 – Календарный учебный график для очно-заочной формы обучения',$normalFont,$tableNameParagraph);
         $table = $section->addTable('standart_table');
         $table->addRow(null,array('tblHeader' => true));
         $table->addCell(null,$cellRowSpan)->addText("Наименование разделов",$tableBoldFont,$cellHCentered);
         $table->addCell(4500,['gridSpan' => $z_days])->addText("Количество академических часов по дням",$tableBoldFont,$cellHCenteredNoSpace);
         $table->addCell(1150,$cellRowSpan)->addText("ИТОГО",$tableBoldFont,$cellHCentered);
         $table->addRow(null,array('tblHeader' => true));
         $table->addCell(null, $cellRowContinue); // пустая
         for ($i = 1; $i <= $z_days; $i++)
         {
            $table->addCell($z_width)->addText('Д'.$i, $tableBoldFont,$cellAllCentered);
         }
         $table->addCell(null, $cellRowContinue); // пустая 
         $busy_cells = 0;
         $free_hours = 4;
         $days_time = [0];
         foreach ($sections as $sect)
         {
            $table->addRow(null);
            $table->addCell(null)->addText($sect->position." ".$sect->name,$tableNormalFont,$cellNoSpace);
            for ($i = 0; $i < $busy_cells; $i++)
            {
                $table->addCell($z_width);
            }
            if ($sect->total_hours < $free_hours)
            { 
                $table->addCell($z_width)->addText($sect->total_hours,$tableNormalFont,$cellHCenteredNoSpace);
                $free_hours -= $sect->total_hours;
                $skip_cells = $z_days - $busy_cells-1;
                $days_time[$busy_cells]+= $sect->total_hours;
            }
            else if ($sect->total_hours == $free_hours)
            {
                $table->addCell($z_width)->addText($sect->total_hours,$tableNormalFont,$cellHCenteredNoSpace);
                $days_time[$busy_cells]+= $sect->total_hours;
                $days_time[$busy_cells+1] = 0;
                $busy_cells++;
                $free_hours = 4;
                $skip_cells = $z_days - $busy_cells;
            }
            else if ($sect->total_hours > $free_hours)
            {
                $to_serve = $sect->total_hours;
                while ($to_serve > 0)
                {
                    if ($to_serve >= $free_hours)
                    {
                        $table->addCell($z_width)->addText($free_hours,$tableNormalFont,$cellHCenteredNoSpace);
                        if ($to_serve > $free_hours) {$skip_cells--;}
                        $to_serve -= $free_hours;
                        $days_time[$busy_cells]+= $free_hours;
                        $days_time[$busy_cells+1] = 0;
                        $busy_cells++;
                        $free_hours = 4;
                    }else{
                        $table->addCell($z_width)->addText($to_serve,$tableNormalFont,$cellHCenteredNoSpace);
                        $free_hours -= $to_serve;
                        $days_time[$busy_cells]+= $to_serve;
                        $to_serve = 0;
                        //$skip_cells = $z_days - $busy_cells;
                    }
                }
            }
            
            for ($i = 0; $i<$skip_cells; $i++)
            {
                $table->addCell($z_width);
            }
            $table->addCell($z_width)->addText($sect->total_hours,$tableBoldFont,$cellHCenteredNoSpace);
         }
         $table->addRow(null);
         $table->addCell()->addText('Всего ак. часов',$tableBoldFont,$cellHCenteredNoSpace);
         if ($days_time[count($days_time)-1] == 0) {array_pop($days_time);}
         foreach ($days_time as $key=>$value)
         {
            $table->addCell($o_width)->addText($value,$tableBoldFont,$cellHCenteredNoSpace);
         }
         $table->addCell()->addText(array_sum($days_time),$tableBoldFont,$cellHCenteredNoSpace);

         $section->addText('',$headFont,$titleParagraph);
         $section->addText('Таблица 5 – Календарный учебный график для заочной формы обучения',$normalFont,$tableNameParagraph);
         $table = $section->addTable('standart_table');
         $table->addRow(null,array('tblHeader' => true));
         $table->addCell(null,$cellRowSpan)->addText("Наименование разделов",$tableBoldFont,$cellHCentered);
         $table->addCell(4500,['gridSpan' => $z_days])->addText("Количество академических часов по дням",$tableBoldFont,$cellHCenteredNoSpace);
         $table->addCell(1150,$cellRowSpan)->addText("ИТОГО",$tableBoldFont,$cellHCentered);
         $table->addRow(null,array('tblHeader' => true));
         $table->addCell(null, $cellRowContinue); // пустая
         for ($i = 1; $i <= $z_days; $i++)
         {
            $table->addCell($z_width)->addText('Д'.$i, $tableBoldFont,$cellAllCentered);
         }
         $table->addCell(null, $cellRowContinue); // пустая 
         $busy_cells = 0;
         $free_hours = 4;
         $days_time = [0];
         foreach ($sections as $sect)
         {
            $table->addRow(null);
            $table->addCell(null)->addText($sect->position." ".$sect->name,$tableNormalFont,$cellNoSpace);
            for ($i = 0; $i < $busy_cells; $i++)
            {
                $table->addCell($z_width);
            }
            if ($sect->total_hours < $free_hours)
            { 
                $table->addCell($z_width)->addText($sect->total_hours,$tableNormalFont,$cellHCenteredNoSpace);
                $free_hours -= $sect->total_hours;
                $skip_cells = $z_days - $busy_cells-1;
                $days_time[$busy_cells]+= $sect->total_hours;
            }
            else if ($sect->total_hours == $free_hours)
            {
                $table->addCell($z_width)->addText($sect->total_hours,$tableNormalFont,$cellHCenteredNoSpace);
                $days_time[$busy_cells]+= $sect->total_hours;
                $days_time[$busy_cells+1] = 0;
                $busy_cells++;
                $free_hours = 4;
                $skip_cells = $z_days - $busy_cells;
            }
            else if ($sect->total_hours > $free_hours)
            {
                $to_serve = $sect->total_hours;
                while ($to_serve > 0)
                {
                    if ($to_serve >= $free_hours)
                    {
                        $table->addCell($z_width)->addText($free_hours,$tableNormalFont,$cellHCenteredNoSpace);
                        if ($to_serve > $free_hours) {$skip_cells--;}
                        $to_serve -= $free_hours;
                        $days_time[$busy_cells]+= $free_hours;
                        $days_time[$busy_cells+1] = 0;
                        $busy_cells++;
                        $free_hours = 4;
                    }else{
                        $table->addCell($z_width)->addText($to_serve,$tableNormalFont,$cellHCenteredNoSpace);
                        $free_hours -= $to_serve;
                        $days_time[$busy_cells]+= $to_serve;
                        $to_serve = 0;
                        //$skip_cells = $z_days - $busy_cells;
                    }
                }
            }
            
            for ($i = 0; $i<$skip_cells; $i++)
            {
                $table->addCell($z_width);
            }
            $table->addCell($z_width)->addText($sect->total_hours,$tableBoldFont,$cellHCenteredNoSpace);
         }
         $table->addRow(null);
         $table->addCell()->addText('Всего ак. часов',$tableBoldFont,$cellHCenteredNoSpace);
         if ($days_time[count($days_time)-1] == 0) {array_pop($days_time);}
         foreach ($days_time as $key=>$value)
         {
            $table->addCell($o_width)->addText($value,$tableBoldFont,$cellHCenteredNoSpace);
         }
         $table->addCell()->addText(array_sum($days_time),$tableBoldFont,$cellHCenteredNoSpace);
        /* 1.6 */
        $section->addText('',$headFont,$titleParagraph);
        $section->addTitle('1.6 Рабочая программа дисциплины',2);
        /* 1.6.1 */
        $section->addText('1.6.1 Учебно-тематический план содержания тем лекционных занятий',$headFont,$titleParagraph); 

        /* 1.6.2 */
        $section->addText('1.6.2 Учебно-тематический план содержания практических занятий',$headFont,$titleParagraph); 
        $section->addText('Таблица 6 – Содержание практических занятий',$normalFont,$tableNameParagraph);
        $table = $section->addTable('standart_table');
        $table->addRow(1701,array('tblHeader' => true));
        $table->addCell(566,$cellRowSpanVerticalDirection)->addText("№ раздела",$tableBoldFont,$cellHCentered);
        $table->addCell(2976)->addText("Темы практических занятий",$tableBoldFont,$cellHCenteredNoSpace);
        $table->addCell(850,$cellRowSpanVerticalDirection)->addText("Трудоемкость, ак. час",$tableBoldFont,$cellHCenteredNoSpace);
        $table->addCell(708,$cellRowSpanVerticalDirection)->addText("Текущий контроль",$tableBoldFont,$cellHCenteredNoSpace);
        $table->addCell(null)->addText("Планируемые результаты обучения",$tableBoldFont,$cellHCenteredNoSpace);
        
        /* 1.7 */
        $section->addText('',$headFont,$titleParagraph);
        $section->addTitle('1.7 Организационно-педагогические условия',2);
        $section->addText('Реализация программы осуществляется в полном соответствии с требованиями законодательства Российской Федерации в области образования, нормативными правовыми актами, регламентирующими данное направление деятельности.',$normalFont,$indentParagraph);

        /* 1.7.1 */
        $section->addText('1.7.1 Требования к квалификации педагогических кадров',$headFont,$titleParagraph); 
        $section->addText('Реализация программы обеспечивается педагогическими работниками образовательной организации, а также лицами, привлекаемыми к ее реализации на иных условиях.',$normalFont,$indentParagraph);
        $section->addText('Требования к образованию и обучению: высшее образование.',$normalFont,$indentParagraph);
        $section->addText('Требования к опыту практической работы: опыт работы в области профессиональной деятельности, связанной с применением работником компетенций, подлежащих совершенствованию, формируемых в результате освоения программы (не менее 3-х лет).',$normalFont,$indentParagraph); 
        
        /* 1.7.2 */
        $section->addText('1.7.2 Требования к материально-техническому обеспечению',$headFont,$titleParagraph); 
        $section->addText('Материально-техническое обеспечение (далее – МТО) необходимо для проведения всех видов учебных занятий и аттестации, предусмотренных учебным планом по программе, и соответствует действующим санитарным и противопожарным нормам и правилам.',$normalFont,$indentParagraph);
        $section->addText('МТО содержит специальные помещения: учебные аудитории для проведения лекций, практических (семинарских) занятий и итоговой аттестации (в соответствии с утвержденным расписанием учебных занятий).',$normalFont,$indentParagraph);
        $section->addText('Специальные помещения укомплектованы специализированной мебелью, оборудованием, расходными материалами, программным обеспечением, техническими средствами обучения и иными средствами, служащими для представления учебной информации обучающимся.',$normalFont,$indentParagraph);
        $section->addText('Таблица 7 – Состав МТО',$normalFont,$tableNameParagraph); 
        $table = $section->addTable('standart_table'); 
        $table->addRow(null,array('tblHeader' => true)); 
        $table->addCell(2976)->addText("Наименование",$tableBoldFont,$cellHCenteredNoSpace); 
        $table->addCell(1133)->addText("Кол-во",$tableBoldFont,$cellHCenteredNoSpace);
        $table->addCell(1133)->addText("Ед. изм.",$tableBoldFont,$cellHCenteredNoSpace);
        $table->addCell(4280)->addText("Примечание",$tableBoldFont,$cellHCenteredNoSpace);
        $parent_types= MtoType::where('parent_id','=',null)->get();
        $f_n = 0;
        foreach ($parent_types as $parent_type)
        {
           $children_types =  MtoType::where('parent_id','=',$parent_type->id)->get();
           $this_mtos = Mto::where('type_id','=',$parent_type->id)->where('dpp_id','=',$dpp->id)->orderBy('position')->get();
           if ($children_types->count() != 0 || $this_mtos->count() != 0)
           {
                $f_n++;
                $table->addRow(); 
                $table->addCell(null,['gridSpan' => 4])->addText($f_n." ".$parent_type->name,$tableBoldFont,$cellHCenteredNoSpace);
           }
           $s_n = 0;
           foreach ($this_mtos as $mto)
           {
                $s_n++;
                $table->addRow();
                $table->addCell()->addText($f_n.".".$s_n." ".$mto->name,$tableNormalFont,$cellNoSpace);
                $table->addCell()->addText( $mto->quantity,$tableNormalFont,$cellHCenteredNoSpace); 
                $table->addCell()->addText( $mto->measure,$tableNormalFont,$cellHCenteredNoSpace);  
                $table->addCell()->addText( $mto->note,$tableNormalFont,$cellNoSpace); 
           } 

           foreach ($children_types as $children_type)
           {
                $this_mtos = Mto::where('type_id','=',$children_type->id)->where('dpp_id','=',$dpp->id)->orderBy('position')->get();
                if ($this_mtos->count() != 0)
                {
                    $s_n++;
                    $table->addRow(); 
                    $table->addCell(null,['gridSpan' => 4])->addText($f_n.".".$s_n." ".$children_type->name,$tableNormalFont,$cellNoSpace);
                        
                }
                $t_n = 0;
                foreach ($this_mtos as $mto)
                {
                    $t_n++; 
                    $table->addRow();
                    $table->addCell()->addText($f_n.".".$s_n.".".$t_n." ".$mto->name,$tableNormalFont,$cellNoSpace);
                    $table->addCell()->addText( $mto->quantity,$tableNormalFont,$cellHCenteredNoSpace); 
                    $table->addCell()->addText( $mto->measure,$tableNormalFont,$cellHCenteredNoSpace);  
                    $table->addCell()->addText( $mto->note,$tableNormalFont,$cellNoSpace);    
                } 
           }
        }
        
        
        /* 1.7.3 */
        $section->addText('',$headFont,$titleParagraph);
        $section->addText('1.7.3 Требования к информационному и учебно-методическому обеспечению',$headFont,$titleParagraph); 
        $section->addText('МТО содержит специальные помещения: учебные аудитории для проведения лекций, практических (семинарских) занятий и итоговой аттестации (в соответствии с утвержденным расписанием учебных занятий).',$normalFont,$indentParagraph); 
        $section->addText('Таблица 8 – Учебно-методическая документация, нормативные правовые акты, нормативная техническая документация, иная документация, учебная литература и иные издания, информационные ресурсы',$normalFont,$tableNameParagraph); 
        $table = $section->addTable('standart_table'); 
        $table->addRow(null,array('tblHeader' => false)); 
        $table->addCell(9530)->addText("1 Учебно-методическая документация",$tableBoldFont,$cellHCenteredNoSpace);  
        $table->addRow(null,array('tblHeader' => false)); 
        $table->addCell(9530)->addText("2 Литература",$tableBoldFont,$cellHCenteredNoSpace);  
        $table->addRow(null,array('tblHeader' => false)); 
        $table->addCell(9530)->addText("3 Интернет ресурсы",$tableBoldFont,$cellHCenteredNoSpace);  
        $table->addRow(null,array('tblHeader' => false)); 
        $table->addCell(9530)->addText("4 Электронно-библиотечная система",$tableBoldFont,$cellHCenteredNoSpace);  

        /* 1.7.4 */
        $section->addText('',$headFont,$titleParagraph);
        $section->addText('1.7.4 Общие требования к организации учебного процесса',$headFont,$titleParagraph); 
        $section->addText('Общие требования к организации учебного процесса определяются локальными нормативными актами образовательной организации, реализующей программу.',$normalFont,$indentParagraph);

         /* 1.8 */
         $section->addText('',$headFont,$titleParagraph);
         $section->addTitle('1.8 Формы аттестации',2);
         $section->addText('К итоговой аттестации допускаются обучающиеся, не имеющие академической задолженности и в полном объеме выполнившие учебный план программы.',$normalFont,$indentParagraph);
         $section->addText('Итоговая аттестация проводится в сроки и в формах, предусмотренных учебным планом и календарным графиком учебного процесса.',$normalFont,$indentParagraph);
         $section->addText('Форма итоговой аттестации – зачет.',$normalFont,$indentParagraph);
         $section->addText('Проверка знаний проводится в форме тестирования.',$normalFont,$indentParagraph);
         $section->addText('Проверка умений, навыков проводится в форме выполнения практических заданий в реальных или модельных условиях',$normalFont,$indentParagraph);
         $section->addText('Для прохождения итоговой аттестации необходимо:',$normalFont,$indentParagraph);
         $section->addListItem('выполнить тестовые задания (не менее 75% правильных ответов);', 0, $normalFont,'multilevel_line' ,$indentParagraph);        
         $section->addListItem('выполнить все практические задания.', 0, $normalFont,'multilevel_line' ,$indentParagraph); 

         $section->addText('',$headFont,$titleParagraph);
         $section->addTitle('2 Оценочные материалы',1);
         $section->addText('Оценочные материалы обеспечивают проверку достижения планируемых результатов обучения по программе и используются в процедуре итоговой аттестации.',$normalFont,$indentParagraph);
         $section->addText('Оценочные материалы состоят из базы тестовых заданий и практических заданий.',$normalFont,$indentParagraph);
         $section->addText('Оценочные материалы приведены в приложении А.',$normalFont,$indentParagraph);
         $section->addText('',$headFont,$titleParagraph);
         $section->addTitle('3 Методические материалы',1);
         $section->addText('Комплект документов, входящих в состав методических материалов, содержит:',$normalFont,$indentParagraph);
         $section->addListItem('оценочные материалы (приложение А);', 0, $normalFont,'multilevel_line' ,$indentParagraph); 
         $section->addListItem('конспект лекций (приложение Б);', 0, $normalFont,'multilevel_line' ,$indentParagraph); 
         $section->addListItem('методические указания к организации и проведению практических занятий (приложение В).', 0, $normalFont,'multilevel_line' ,$indentParagraph); 
         
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        try {
            $objWriter->save(storage_path('ПрДПП.docx'));
        } catch (Exception $e) {
        }


        return response()->download(storage_path('ПрДПП.docx'));
    }

    function export_content(Dpp $dpp)
    {
        $phpWord = new PhpWord();
        $phpWord->getSettings()->setThemeFontLang(new Language(Language::RU_RU));
        //$phpWord->setDefaultFontName('Times New Roman');
        //$phpWord->setDefaultFontSize(14);
        //$phpWord->getSettings()->setUpdateFields(true);
        /* Стили */
        // $headFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 14, 'bold' => true);
        // $boldFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 14, 'bold' => true);
        // $normalFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 14, 'bold' => false);
        // $tableLittleFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 10, 'bold' => false);
        // $tableNormalFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 12, 'bold' => false);
        // $tableBoldFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 12, 'bold' => true);
        // $redFont = array('name' => 'Times New Roman','color' => 'red', 'size' => 14, 'bold' => false);
        // $titleParagraph = array('alignment' => 'both','lineHeight' => 1.5,'spaceAfter' => 0,'indentation'=> ['firstLine' => 708.661417323]);        
        // $annotationTitleParagraph = array('alignment' => 'both','lineHeight' => 1.5,'spaceAfter' => 0,'spaceBefore' => 12, 'indentation'=> ['firstLine' => 708.661417323]);        
        // $centerParagraph = array('alignment' => 'center','lineHeight' => 1.5,'spaceAfter' => 0);
        // $rightParagraph = array('alignment' => 'right','lineHeight' => 1.5,'spaceAfter' => 0);
        // $normalParagraph = array('alignment' => 'both','lineHeight' => 1.5,'spaceAfter' => 0);
        // $tableNameParagraph = array('alignment' => 'both','lineHeight' => 1,'spaceAfter' => 0);
        // $indentParagraph = array('alignment' => 'both','lineHeight' => 1.5,'spaceAfter' => 0,'indentation'=> ['firstLine' => 708.661417323]);
        // $normalLineH1Paragraph = array('alignment' => 'both','lineHeight' => 1,'spaceAfter' => 0);
        // $normalLineH1CenterParagraph = array('alignment' => 'center','lineHeight' => 1,'spaceAfter' => 0);
        // $tableFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 11, 'bold' => false);
        // $phpWord->addTitleStyle(0, $headFont,$titleParagraph);
        // $phpWord->addTitleStyle(1, $headFont,$titleParagraph);
        // $phpWord->addTitleStyle(2, $headFont,$titleParagraph);

        // $phpWord->addNumberingStyle(
        //     'multilevel_line',
        //     array(
        //     'type' => 'multilevel',
        //     'levels' => array(
        //     array('format' => 'bullet', 'text' => '–'),
        //     array('format' => 'bullet', 'text' => '–', 'left' => 720, 'hanging' => 360, 'tabPos' => 720),
        //     )));

        // $tableStyle = array( 'borderColor' => '000000', 'borderSize'  => 1, 'cellMarginLeft' => 107.716535433,'cellMarginRight' => 107.716535433, 'cellMarginTop'  => 0,'cellMarginBottom'  => 0,'width'=> '100%');
        // $tableWOBordersStyle = array('cellMargin'  => 50,'width'=> '100%');
        
        // $firstRowStyle = array();
        // $phpWord->addTableStyle('standart_table', $tableStyle);
        // $phpWord->addTableStyle('wo_borders_table', $tableWOBordersStyle, $firstRowStyle);
        
        // $cellRowSpan = array('vMerge' => 'restart', 'valign' => 'center');
        // $cellRowContinue = array('vMerge' => 'continue');
        // $cellColSpan2 = array('gridSpan' => 2, 'valign' => 'center');
        // $cellColSpan3 = array('gridSpan' => 3, 'valign' => 'center');
        // $cellHCentered = array('align' => 'center');
        // $cellHCenteredNoSpace = array('align' => 'center','spaceAfter' => 0);
        // $cellNoSpace = array('spaceAfter' => 0,'lineHeight' => 1);
        // $cellAllCentered = array('align' => 'center', 'valign' => 'center','spaceAfter' => 0);
        // $cellVCentered = array('valign' => 'center');
        // $cellVerticalDirection = array('textDirection' => 'btLr','align' => 'center');
        // $cellRowSpanVerticalDirection = array('textDirection' => 'btLr','vMerge' => 'restart', 'valign' => 'center');
            
        /* Section */
        $sectionStyle = array(
            'marginTop' => 1133.8582677,
            'marginBottom' => 1133.8582677,
            'marginLeft' => 1700.7874016,
            'marginRight' => 850.39370079,
            'pageNumberingStart' => 2,
        );
        $titleSectionStyle = array(
            'marginTop' => 1133.8582677,
            'marginBottom' => 2000,
            'marginLeft' => 1700.7874016,
            'marginRight' => 850.39370079,
            'footerHeight'=>1134
        );


        $objReader = \PhpOffice\PhpWord\IOFactory::createReader('Word2007');
        $source = storage_path('test_docx.docx'); 
        $phpWord = $objReader->load($source); 
        $sections = $phpWord->getSections();
        foreach($phpWord->getSections() as $ks => $section) 
        {
            $elems = $section->getElements();
            foreach ($elems as $ke => $elem)
            {
                $class = class_basename($elem);

                if ($class == 'TextRun')
                {
                    $elem->getParagraphStyle()->setIndentation(['firstLine' => 708.661417323]);
                    foreach ($elem->getElements() as $part)
                    {
                        //$part->getParagraphStyle()->setIndentation(2500);
                    }
                }
              //  $elems[$ke] = $elem;
            }
            //$section->setElements($elems);
            //dd($section);
            //dd($sections[$ks]);
            //= $section;
        }
        
       // $newDoc = new PhpWord();
       // $newDoc->getSettings()->setThemeFontLang(new Language(Language::RU_RU));
        
        // foreach($phpWord->getSections() as $section)
        // {
        //      dd($section);
        //     $a = $newDoc->addSection($section);
        //     dd($a);
        // }
        
       //dd($phpWord);
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save(storage_path('test_doc.docx'));
        return response()->download(storage_path('test_doc.docx'));
        //$htmlWriter = new \PhpOffice\PhpWord\Writer\HTML($phpWord);
        //dd($arrays);
        // $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        // try {
        //     $objWriter->save(storage_path('test.docx'));
        // } catch (Exception $e) {
        // }
        //$phpWord = \PhpOffice\PhpWord\IOFactory::load(storage_path('test_doc.docx'));
        //$htmlWriter = new \PhpOffice\PhpWord\Writer\HTML($phpWord);
        //$htmlWriter->save(storage_path('test_doc.html'));

       
    }

    public function export_om (Dpp $dpp)
    {
        $t = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('/templates/make_om.docx'));
        //ТИТУЛ
        $t->setValue('dppName', $dpp->name);
        $t->setValue('year', date("Y"));

        //ТАБЛИЦА 2 
        $zv = $dpp->zun_version_id;
        $competences = Competence::where('zun_version_id','=',$zv)->get();
        $t->cloneRow('competence', $competences->count());
        foreach ($competences as $index => $competence)
        {
            $i = $index +1;
            $t->setValue('competence#'.$i, $competence->name);
            
            $knowledges = Knowledge::where('zun_version_id','=',$zv)->get()->pluck("name")->toArray();
            $knowledges = implode("; ",$knowledges);
            $t->setValue('knowledges#'.$i, $knowledges);

            $abilities = Ability::where('zun_version_id','=',$zv)->get()->pluck("name")->toArray();
            $abilities = implode("; ",$abilities);
            $t->setValue('abilities#'.$i, $abilities);

            $skills = Skill::where('zun_version_id','=',$zv)->get()->pluck("name")->toArray();
            $skills = implode("; ",$skills);
            $t->setValue('skills#'.$i, $skills);
        }

        // ТАБЛИЦА 3 
        //$t->cloneBlock('qtn', 3);
        $t->cloneBlock('CLONEME', 3);
        $knowledges = Knowledge::where('zun_version_id','=',$zv)->get();
        $t->cloneRow('knowledge', $knowledges->count());
       
        foreach ($knowledges as  $index => $knowledge)
        {
            $i = $index + 1;
            
            
            $t->setValue('knowledge#'.$i, $knowledge->name);
        }
        
        $pathToSave = storage_path('test_om.docx');
        $t->saveAs($pathToSave);
        return response()->download(storage_path('test_om.docx'));
    }
}
