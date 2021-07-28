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

    public function export_dpp(Dpp $dpp)
    {
        $phpWord = new PhpWord();
        $phpWord->getSettings()->setThemeFontLang(new Language(Language::RU_RU));
        /* Стили */
        $headFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 14, 'bold' => true);
        $boldFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 14, 'bold' => true);
        $normalFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 14, 'bold' => false);
        $littleTableFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 10, 'bold' => false);
        $redFont = array('name' => 'Times New Roman','color' => 'red', 'size' => 14, 'bold' => false);
        $titleParagraph = array('alignment' => 'both','lineHeight' => 1.5,'spaceAfter' => 0,'indentation'=> ['firstLine' => 708.661417323]);        
        $centerParagraph = array('alignment' => 'center','lineHeight' => 1.5,'spaceAfter' => 0);
        $normalParagraph = array('alignment' => 'both','lineHeight' => 1.5,'spaceAfter' => 0);
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
            )
            )
            );

        $tableStyle = array( 'borderColor' => '000000', 'borderSize'  => 1, 'cellMargin'  => 50,'width'=> '100%');
        $tableWOBordersStyle = array('cellMargin'  => 50,'width'=> '100%');
        
        $firstRowStyle = array('bgColor' => '000');
        $phpWord->addTableStyle('standart_table', $tableStyle, $firstRowStyle);
        $phpWord->addTableStyle('wo_borders_table', $tableWOBordersStyle, $firstRowStyle);

        /* Section */
        $sectionStyle = array(
            'marginTop' => 1133.8582677,
            'marginBottom' => 1133.8582677,
            'marginLeft' => 1700.7874016,
            'marginRight' => 850.39370079
        );
        $section = $phpWord->addSection($sectionStyle);
        /* ТИТУЛ */

        $section->addText('МИНИСТЕРСТВО ТРАНСПОРТА РОССИЙСКОЙ ФЕДЕРАЦИИ',$boldFont,$centerParagraph);
        $section->addText('',$boldFont,$centerParagraph);
        $section->addText('ФЕДЕРАЛЬНОЕ АВТОНОМНОЕ УЧРЕЖДЕНИЕ',$boldFont,$centerParagraph);
        $section->addText('«РОССИЙСКИЙ ДОРОЖНЫЙ НАУЧНО-ИССЛЕДОВАТЕЛЬСКИЙ ИНСТИТУТ» (ФАУ «РОСДОРНИИ»)',$boldFont,$centerParagraph);
        $section->addText('',$boldFont,$centerParagraph);
        $section->addText('',$boldFont,$centerParagraph);
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
        $section->addText('ПРИМЕРНАЯ',$boldFont,$centerParagraph);
        $section->addText('ДОПОЛНИТЕЛЬНАЯ ПРОФЕССИОНАЛЬНАЯ ПРОГРАММА –',$boldFont,$centerParagraph);
        $section->addText('ПРОГРАММА ПОВЫШЕНИЯ КВАЛИФИКАЦИИ',$boldFont,$centerParagraph);
        $section->addText('«'.mb_strtoupper($dpp->name).'»',$normalFont,$centerParagraph);
        $section->addText('по направлению подготовки 38.03.01 «Экономика»',$redFont,$centerParagraph);
        $section->addText('по специальности 38.02.06 «Финансы»',$redFont,$centerParagraph);
        $len = 13 - floor(strlen($dpp->name) / 40);
        for ($i = 0; $i < $len; $i++)
        {
            $section->addText(' ',$boldFont,$centerParagraph);
        }
        
        $section->addText('Москва '.date ( 'Y' ),$boldFont,$centerParagraph);
        $section->addPageBreak();

        /* Разработчики */
        $section->addText('Список разработчиков',$boldFont,$centerParagraph);
        $table = $section->addTable('wo_borders_table');

        for ($i = 0; $i < 10; $i++)
        {
            $table->addRow();
            $cell=$table->addCell(3123,779527559);
            $cell=$table->addCell(272,125984252);
            $cell=$table->addCell(1094,173228346);
            $cell=$table->addCell(272,125984252);
            $cell=$table->addCell(4773,543307087);
            $table->addRow();
            $cell=$table->addCell(3123,779527559)->addText('ученое звание, ученая степень',$littleTableFont,$normalLineH1CenterParagraph);
            $cell=$table->addCell(272,125984252);
            $cell=$table->addCell(1094,173228346)->addText('подпись',$littleTableFont,$normalLineH1CenterParagraph);
            $cell=$table->addCell(272,125984252);
            $cell=$table->addCell(4773,543307087)->addText('ФИО',$littleTableFont,$normalLineH1CenterParagraph);
        }

        $section->addPageBreak();
        /* Содержание */
        $section->addText('Содержание',$boldFont,$centerParagraph);
        $section->addPageBreak();
        /* 1.1.1 */ 
        $section->addTitle('1 Общая характеристика программы',0);
        $section->addTitle('1.1 Общие положения',1);
        $section->addTitle('1.1.1 Нормативные правовые основания разработки',2);
        $section->addText('Нормативные правовые основания для разработки примерной дополнительной профессиональной программы – программы повышения квалификации «'.$dpp->name.'» (далее – программа) составляют:',$normalFont,$indentParagraph);
        $section->addListItem('Федеральный закон от 29 декабря 2012 г. № 273-ФЗ «Об образовании в Российской Федерации»;', 0, $normalFont,'multilevel_line' ,$indentParagraph);
        $section->addListItem('Федеральный закон от 03 июля 2016 г. № 238-ФЗ «О независимой оценке квалификации»;', 0, $normalFont,'multilevel_line' ,$indentParagraph);
        $section->addListItem('Постановление Правительства Российской Федерации от 22 января 2013 г. № 23 «О Правилах разработки, утверждения и применения профессиональных стандартов»;', 0, $normalFont,'multilevel_line' ,$indentParagraph);
        $section->addListItem('приказ Минтруда России от 12 апреля 2013 г. № 148н «Об утверждении уровней квалификаций в целях разработки проектов профессиональных стандартов»;', 0, $normalFont,'multilevel_line' ,$indentParagraph);
        $section->addListItem('приказ Минобрнауки России от 01 июля 2013 г. № 499 «Об утверждении Порядка организации и осуществления образовательной деятельности по дополнительным профессиональным программам»;', 0, $normalFont,'multilevel_line' ,$indentParagraph);
        $section->addListItem('приказ Минтруда России от 01 ноября 2016 г. № 601н «Об утверждении Положения о разработке оценочных средств для проведения независимой оценки квалификации».', 0, $normalFont,'multilevel_line' ,$indentParagraph);		
        

        
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        try {
            $objWriter->save(storage_path('ПрДПП.docx'));
        } catch (Exception $e) {
        }


        return response()->download(storage_path('ПрДПП.docx'));
    }
}
