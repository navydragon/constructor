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
use App\Lection;
use App\Nsi;
use App\Task;
use \PhpOffice\PhpWord\PhpWord;
use \PhpOffice\PhpWord\Style\Language;
use SimpleXMLElement;
use Illuminate\Support\Facades\Storage;
use File;

use \DocxMerge\DocxMerge;
class ExportController extends Controller
{
    public function export_zun(Dpp $dpp,$zv,$type)
    {
        $phpWord = new PhpWord();
        $phpWord->getSettings()->setThemeFontLang(new Language(Language::RU_RU));
        //$phpWord->setDefaultFontName('dejavu sans');
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(14);
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
        if ($type =='word')
        {
            $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
            $filename = 'Результаты.docx';
        }else if ($type =='pdf'){
            $pdfPath = base_path('vendor/tecnickcom/tcpdf');
            //$pdfPath = base_path('vendor/dompdf/dompdf');
            \PhpOffice\PhpWord\Settings::setPdfRendererPath($pdfPath);
            \PhpOffice\PhpWord\Settings::setPdfRendererName('TCPDF');
            $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'PDF');
            //$objWriter->setFont('dejavusans');
            $objWriter->setFont('freeserif', '', 12);

            $filename = 'Результаты.pdf';
        }

        try {
            $objWriter->save(storage_path($filename));
        } catch (Exception $e) {
        }


        return response()->download(storage_path($filename));
    }

    public function export_zun_justification(Dpp $dpp)
    {
        $phpWord = new PhpWord();
        $phpWord->getSettings()->setThemeFontLang(new Language(Language::RU_RU));
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(14);

        $t = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('/templates/justification.docx'));

        $t->setValue('dppName', $dpp->name);
        $zv = $dpp->zun_version_id;
        $zuns = 0;
        $zuns += Competence::where('zun_version_id','=',$zv)->get()->count();
        $zuns += Skill::where('zun_version_id','=',$zv)->get()->count();
        $zuns += Ability::where('zun_version_id','=',$zv)->get()->count();
        $zuns += Knowledge::where('zun_version_id','=',$zv)->get()->count();
        $competences = Competence::where('zun_version_id','=',$zv)->get();
        $i = 0;
        $t->cloneRow('code',$zuns+1);
        $sc = 0; $ac = 0;
        foreach ($competences as $competence)
        {
            $i++;
            $t->setValue('code#'.$i, " ");
            $t->setValue('name#'.$i, $competence->name);
            $t->setValue('type#'.$i, "-");
            $t->setValue('text#'.$i, "-");
            $skills = Skill::where('competence_id','=',$competence->id)->orderBy('position','asc')->get();
            foreach ($skills as $skill)
            {
                $i++; $sc++;
                $t->setValue('code#'.$i, "Н(".$sc.")");
                $t->setValue('name#'.$i, $skill->name);
                $type = $skill->is_by_expert ? "Эксперт" : "НСИ";
                $just = $type == "Эксперт" ? $skill->expert_answer : $skill->nsis()->pluck("nsiFullName")->toArray();
                $just = "– ".implode(";                                                                                         – ",$just);
                if (isset($skill->note)) {
                    $just .= " (".$skill->note.")";
                }
                $t->setValue('type#'.$i, $type);
                $t->setValue('text#'.$i, $just);
                $abilities = Ability::where('skill_id','=',$skill->id)->orderBy('position','asc')->get();
                foreach ($abilities as $ability)
                {
                    $i++; $ac++;
                    $t->setValue('code#'.$i, "У(".$sc."".$ac.")");
                    $t->setValue('name#'.$i, $ability->name);
                    $type = $ability->is_by_expert ? "Эксперт" : "НСИ";
                    $just = $type == "Эксперт" ? $ability->expert_answer : $ability->nsis()->pluck("nsiFullName")->toArray();
                    $just = "– ".implode(";                                                                                         – ",$just);
                    if (isset($ability->note)) {
                        $just .= " (".$ability->note.")";
                    }
                    $t->setValue('type#'.$i, $type);
                    $t->setValue('text#'.$i, $just);
                    $knowledges = Knowledge::where('ability_id','=',$ability->id)->orderBy('position','asc')->get();
                    foreach ($knowledges as $key=>$knowledge)
                    {
                        $i++; $k = $key +1;
                        $t->setValue('code#'.$i, "З(".$sc."".$ac."-".$k.")");
                        $t->setValue('name#'.$i, $knowledge->name);
                        $type = $knowledge->is_by_expert ? "Эксперт" : "НСИ";
                        $just = $type == "Эксперт" ? $knowledge->expert_answer : $knowledge->nsis()->pluck("nsiFullName")->toArray();
                        $just = "– ".implode(";                                                                                         – ",$just);
                        if (isset($knowledge->note)) {
                            $just .= " (".$knowledge->note.")";
                        }
                        $t->setValue('type#'.$i, $type);
                        $t->setValue('text#'.$i, $just);
                    }
                }
            }
            $abilities = Ability::where('competence_id','=',$competence->id)->orderBy('position','asc')->get();
            foreach ($abilities as $ability)
            {
                $i++; $ac++;
                $t->setValue('code#'.$i, "У(".$ac.")");
                $t->setValue('name#'.$i, $ability->name);
                $type = $ability->is_by_expert ? "Эксперт" : "НСИ";
                $just = $type == "Эксперт" ? $ability->expert_answer : $ability->nsis()->pluck("nsiFullName")->toArray();
                $just = "– ".implode(";                                                                                         – ",$just);
                if (isset($ability->note)) {
                    $just .= " (".$ability->note.")";
                }
                $t->setValue('type#'.$i, $type);
                $t->setValue('text#'.$i, $just);
                $knowledges = Knowledge::where('ability_id','=',$ability->id)->orderBy('position','asc')->get();
                foreach ($knowledges as $key=>$knowledge)
                {
                    $i++; $k = $key+1;
                    $t->setValue('code#'.$i, "З(".$ac."-".$k.")");
                    $t->setValue('name#'.$i, $knowledge->name);
                    $type = $knowledge->is_by_expert ? "Эксперт" : "НСИ";
                    $just = $type == "Эксперт" ? $knowledge->expert_answer : $knowledge->nsis()->pluck("nsiFullName")->toArray();
                    if (is_array($just))
                    {
                        $just = "– ".implode(";                                                                                         – ",$just);
                    }
                    if (isset($knowledge->note)) {
                        $just .= " (".$knowledge->note.")";
                    }
                    $t->setValue('type#'.$i, $type);
                    $t->setValue('text#'.$i, $just);
                }
            }
        }
        $skills = Skill::where('competence_id','=',null)->where('zun_version_id','=',$zv)->orderBy('position','asc')->get();
        foreach ($skills as $skill)
        {
            $i++; $sc++;
            $t->setValue('code#'.$i, "Н(".$sc.")");
            $t->setValue('name#'.$i, $skill->name);
            $type = $skill->is_by_expert ? "Эксперт" : "НСИ";
            $just = $type == "Эксперт" ? $skill->expert_answer : $skill->nsis()->pluck("nsiFullName")->toArray();
            $just = implode(";                                                                                         – ",$just);
            if (isset($skill->note)) {
                $just .= " (".$skill->note.")";
            }
            $t->setValue('type#'.$i, $type);
            $t->setValue('text#'.$i, $just);

            $abilities = Ability::where('skill_id','=',$skill->id)->get();
            foreach ($abilities as $ability)
            {
                $i++; $ac++;
                $t->setValue('code#'.$i, "У(".$sc."".$ac.")");
                $t->setValue('name#'.$i, $ability->name);
                $type = $ability->is_by_expert ? "Эксперт" : "НСИ";
                $just = $type == "Эксперт" ? $ability->expert_answer : $ability->nsis()->pluck("nsiFullName")->toArray();
                if (is_array($just))
                {
                    $just = "– ".implode(";                                                                                         – ",$just);
                }
                if (isset($ability->note)) {
                    $just .= " (".$ability->note.")";
                }
                $t->setValue('type#'.$i, $type);
                $t->setValue('text#'.$i, $just);
                $knowledges = Knowledge::where('ability_id','=',$ability->id)->get();
                foreach ($knowledges as $key=>$knowledge)
                {
                    $i++; $k = $key+1;
                    $t->setValue('code#'.$i, "З(".$sc."".$ac."-".$k.")");
                    $t->setValue('name#'.$i, $knowledge->name);
                    $type = $knowledge->is_by_expert ? "Эксперт" : "НСИ";
                    $just = $type == "Эксперт" ? $knowledge->expert_answer : $knowledge->nsis()->pluck("nsiFullName")->toArray();
                    if (is_array($just))
                    {
                        $just = "– ".implode(";                                                                                         – ",$just);
                    }
                    if (isset($knowledge->note)) {
                        $just .= " (".$knowledge->note.")";
                    }
                    $t->setValue('type#'.$i, $type);
                    $t->setValue('text#'.$i, $just);
                }
            }
        }
        $abilities = Ability::where('competence_id','=',null)->where('skill_id','=',null)->where('zun_version_id','=',$zv)->orderBy('position','asc')->get();
        foreach ($abilities as $ability)
        {
            $i++; $ac++;
            $t->setValue('code#'.$i, "У(".$ac.")");
            $t->setValue('name#'.$i, $ability->name);
            $type = $ability->is_by_expert ? "Эксперт" : "НСИ";
            $just = $type == "Эксперт" ? $ability->expert_answer : $ability->nsis()->pluck("nsiFullName")->toArray();
            if (is_array($just))
            {
                $just = "– ".implode(";                                                                                         – ",$just);
            }
            if (isset($ability->note)) {
                $just .= " (".$ability->note.")";
            }
            $t->setValue('type#'.$i, $type);
            $t->setValue('text#'.$i, $just);
            $knowledges = Knowledge::where('ability_id','=',$ability->id)->get();
            foreach ($knowledges as $key=>$knowledge)
            {
                $i++; $k = $key+1;
                $t->setValue('code#'.$i, "З(".$ac."-".$k.")");
                $t->setValue('name#'.$i, $knowledge->name);
                $type = $knowledge->is_by_expert ? "Эксперт" : "НСИ";
                $just = $type == "Эксперт" ? $knowledge->expert_answer : $knowledge->nsis()->pluck("nsiFullName")->toArray();
                if (is_array($just))
                {
                    $just = "– ".implode(";                                                                                         – ",$just);
                }
                if (isset($knowledge->note)) {
                    $just .= " (".$knowledge->note.")";
                }
                $t->setValue('type#'.$i, $type);
                $t->setValue('text#'.$i, $just);
            }
        }
        $i++;
        $t->setValue('code#'.$i, "-");
        $t->setValue('name#'.$i, 'СКВОЗНЫЕ ЗНАНИЯ');
        $t->setValue('type#'.$i, "-");
        $t->setValue('text#'.$i, "-");
        $th_knowledges = Knowledge::where('zun_version_id',$zv)->where('is_through',true)->orderBy('position','asc')->get();
        foreach ($th_knowledges as $key => $knowledge)
        {
            $i++; $k = $key+1;
            $t->setValue('code#'.$i, "СЗ(".$k.")");
            $t->setValue('name#'.$i, $knowledge->name);
            $type = $knowledge->is_by_expert ? "Эксперт" : "НСИ";
            $just = $type == "Эксперт" ? $knowledge->expert_answer : $knowledge->nsis()->pluck("nsiFullName")->toArray();
            if (is_array($just))
            {
                $just = "– ".implode(";                                                                                         – ",$just);
            }
            if ($knowledge->note) {
                $just .= " (".$knowledge->note.")";
            }
            $t->setValue('type#'.$i, $type);
            $t->setValue('text#'.$i, $just);
        }

        $pathToSave = storage_path('Обоснование.docx');
        $t->saveAs($pathToSave);
        return response()->download(storage_path('Обоснование.docx'));
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
        //$questions = Question::
        $questions = Question::whereHas('knowledge')->where('om_version_id',$om)->orderBy('knowledge_id','asc')->get();
        //dd($questions->count());
        $section->addText("Всего вопросов: ".count($questions), $fontStyle);
        foreach ($questions as $question)
        {
            if ($q < 2000)
            {
                if ($question->knowledge_id != $kn)
                {
                    $kn = $question->knowledge_id;
                    $section->addText("Знание: «".$question->knowledge->name."» (количество вопросов: ".$question->knowledge->questions()->count().")", $boldStyle);
                }
                $text = str_replace('&','&#38;',$question->text);
                $text = str_replace('<','&#60;',$text);
                $text = str_replace('>','&#62;',$text);

                $section->addText($q.") ".$text." (Тип вопроса: ".$question->type->name.")", $fontStyle);

                if (!is_null($question->image))
                {
                    $section->addText("Изображение в тексте вопроса:", $fontStyle);
                    $section->addImage(
                        Storage::disk('public')->get($question->image),
                        // array(
                        //     'height'        => 100,
                        // )
                    );
                }

                switch ($question->question_type_id) {
                    case 1:
                        $section->addText("Варианты ответов:", $fontStyle);
                        foreach ($question->single_choice_answers as $answer)
                        {
                                $text = str_replace('&','&#38;',$answer->text);
                                $text = str_replace('<','&#60;',$text);
                                $text = str_replace('>','&#62;',$text);

                                if ($answer->is_right == true) {$prefix="(+) ";}else{$prefix="";}
                                $section->addListItem($prefix.$text, 0, $fontStyle);
                                if (!is_null($answer->image))
                                {
                                    $section->addText("Изображение в тексте ответа:", $fontStyle);
                                    try {
                                      $section->addImage(
                                          Storage::disk('public')->get($answer->image),
                                      );
                                    }catch(Exception $e)
                                    {
                                      dd("KEK");
                                    }
                                }
                        }
                    break;

                    case 2:
                        $section->addText("Варианты ответов:", $fontStyle);
                        foreach ($question->multi_choice_answers as $answer)
                        {
                            $text = str_replace('&','&#38;',$answer->text);
                            $text = str_replace('<','&#60;',$text);
                            $text = str_replace('>','&#62;',$text);

                            //$text = mb_convert_encoding($text, "KOI8-R");
                            //if ($q == 224) {dd($text);}
                            if ($answer->is_right == true) {$prefix="(+) ";}else{$prefix="";}
                            $section->addListItem($prefix.$text, 0, $fontStyle);
                            if (!is_null($answer->image))
                            {
                                $section->addText("Изображение в тексте ответа:", $fontStyle);
                                $section->addImage(
                                    Storage::disk('public')->get($answer->image),
                                );
                            }
                        }
                    break;

                    case 3:
                        $section->addText("Варианты правильных ответов:", $fontStyle);
                        foreach ($question->free_choice_answers as $answer)
                        {
                            $text = str_replace('&','&#38;',$answer->text);
                            $text = str_replace('<','&#60;',$text);
                            $text = str_replace('>','&#62;',$text);
                            $section->addListItem ($text, 0, $fontStyle);
                        }
                    break;

                    case 4:
                        $section->addText("Правильная последовательность ответов:", $fontStyle);
                        foreach ($question->sequence_choice_answers as $answer)
                        {
                            $text = str_replace('&','&#38;',$answer->text);
                            $text = str_replace('<','&#60;',$text);
                            $text = str_replace('>','&#62;',$text);
                            $section->addListItem($text, 0, $fontStyle);
                            if (!is_null($answer->image))
                            {
                                $section->addText("Изображение в тексте ответа:", $fontStyle);
                                $section->addImage(
                                    Storage::disk('public')->get($answer->image),
                                  //  array('height'        => 100,)
                                );
                            }
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
                            $text = str_replace('&','&#38;',$answer->text);
                            $text = str_replace('<','&#60;',$text);
                            $text = str_replace('>','&#62;',$text);
                            $text2 = str_replace('&','&#38;',$answer->text2);
                            $text2 = str_replace('<','&#60;',$text2);
                            $text2 = str_replace('>','&#62;',$text2);
                            $table->addCell(6000)->addText($text,$fontStyle);
                            $table->addCell(6000)->addText($text2,$fontStyle);
                        }
                    break;

                    default:
                        # code...
                        break;
                }
                $q++;
            }
        }




        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        try {
            $objWriter->save(storage_path($dpp->abbreveation.'_Тестовые вопросы.docx'));
        } catch (Exception $e) {
        }


        return response()->download(storage_path($dpp->abbreveation.'_Тестовые вопросы.docx'));
    }


    public function export_om_tasks(Dpp $dpp)
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
        $phpWord->setDefaultFontSize(14);
        $phpWord->setDefaultFontName('Times New Roman');

        /* Section */
        $section = $phpWord->addSection();
        /* Заголовок */
        $text='Задания ДПП «'.$dpp->name.'»';
        $section->addTitle($text, 0);

        $tasks = $dpp->om_version->tasks;
        $section->addText("Всего заданий: ".count($tasks), $fontStyle);

        foreach ($tasks as $task)
        {
            $section->addText($task->name." ".$task->position, $boldStyle);
            $section->addText("Тип задания", $boldStyle);
            $section->addText($task->task_type->name, $fontStyle);

            switch ($task->task_type_id) {
                case 1:
                    $section->addText("Описание ситуации и постановка задачи", $boldStyle);
                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $task->description);
                    $section->addText("Место выполнения", $boldStyle);
                    $section->addText($task->place, $fontStyle);
                    $section->addText("Максимальное время выполнения: ".$task->time." минут", $fontStyle);
                    $section->addText("Критерии оценки", $boldStyle);
                    foreach ($task->subjects as $subject)
                    {
                        $section->addText("Предмет оценки: «".$subject->name."»", $fontStyle);
                        $table = $section->addTable('standart_table');
                        $table->addRow();
                        $table->addCell(6000)->addText("Объект оценки",$fontStyle);
                        $table->addCell(6000)->addText("Модельный элемент",$fontStyle);
                        foreach ($subject->objects as $object)
                        {
                            $table->addRow();
                            $table->addCell(6000)->addText(mb_convert_encoding($object->name,'UTF-8','auto'),$fontStyle);
                            $text = $this->clean_text($object->model_answer);
                            $table->addCell(6000)->addText($text,$fontStyle);
                        }
                    }
                    break;
                case 2:
                    $section->addText("Описание проекта и исходные данные", $boldStyle);
                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $task->description);
                    $section->addText("Инструкция по выполнению проекта", $boldStyle);
                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $task->portfolioProcedure);
                    $section->addText("Как будет оцениваться проект", $boldStyle);
                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $task->portfolioCriteria);
                    $section->addText("Предметы оценки:", $boldStyle);
                    foreach ($task->subjects as $subject)
                    {
                        $section->addListItem($subject->name, 0, $fontStyle);
                    }
                    break;
                case 3:
                    $section->addText("Описание ситуации и постановка задачи", $boldStyle);
                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $task->description);
                    $section->addText("Место выполнения", $boldStyle);
                    $section->addText($task->place, $fontStyle);
                    $section->addText("Максимальное время выполнения: ".$task->time." минут", $fontStyle);
                    $section->addText("Предметы оценки:", $boldStyle);
                    foreach ($task->subjects as $subject)
                    {
                        $section->addListItem($subject->name, 0, $fontStyle);
                    }
                    $section->addText("Этапы выполнения задания", $boldStyle);
                    foreach ($task->steps as $step)
                    {
                        $section->addText("Шаг №".$step->position, $boldStyle);
                        $section->addText("Текст задания", $boldStyle);
                        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $step->text);
                        $section->addText("Объект оценки", $boldStyle);
                        $section->addText($step->object, $fontStyle);
                        $section->addText("Модельный ответ", $boldStyle);
                        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $step->rightAnswer);
                        $section->addText("Подсказка", $boldStyle);
                        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $step->help);
                    }
                    break;
                default:
                    # code...
                    break;
            }

            $section->addText("", $fontStyle);
        }

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        try {
            $objWriter->save(storage_path($dpp->abbreveation.'_Задания.docx'));
        } catch (Exception $e) {
        }


        return response()->download(storage_path($dpp->abbreveation.'_Задания.docx'));
    }

    public function export_learning_plan(Dpp $dpp)
    {
        if ($dpp->type->id == 1)
        {
            return $this->export_learning_plan_pk($dpp);
        }
        return $this->export_learning_plan_pp($dpp);
    }

    public function export_learning_plan_pp(Dpp $dpp)
    {
        $sv = $dpp->st_version;

        $t = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('/templates/yp_pp.docx'));
        \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true);

        $sect_count = StructureSection::where('st_version_id', '=', $sv->id)->get()->count();
        $t->cloneRow('tname', $sect_count);

        $sections = StructureSection::with(['themes' => function ($query) {
            $query->orderBy('position');
        }])
            ->where('parent_id', '=', null)
            ->where('st_version_id', '=', $sv->id)
            ->orderBy('position')
            ->get();

        $t->setValue('dppName', $dpp->name);
        $number = 0;
        $sec_number = 0;
        $total = [0, 0, 0, 0, 0, 0, 0, 0];
        foreach ($sections as $sect) {
            $number++;
            $sec_number++;
            $t->setValue('tn#' . $number, $sec_number);
            $t->setValue('tname#' . $number, $this->remove_nbsp($sect->name));
            $t->setValue('ttotal#' . $number, $sect->total_hours);
            $total[0] += $sect->total_hours;
            $t->setValue('tlo#' . $number, $sect->lection_hours_o == 0 ? "" : $sect->lection_hours_o);
            $total[1] += $sect->lection_hours_o;
            $t->setValue('tlz#' . $number, $sect->lection_hours_z == 0 ? "" : $sect->lection_hours_z);
            $total[2] += $sect->lection_hours_z;
            $t->setValue('tpo#' . $number, $sect->practice_hours_o == 0 ? "" : $sect->practice_hours_o);
            $total[3] += $sect->practice_hours_o;
            $t->setValue('tpz#' . $number, $sect->practice_hours_z == 0 ? "" : $sect->practice_hours_z);
            $total[4] += $sect->practice_hours_z;
            $t->setValue('tko#' . $number, $sect->consult_hours_o == 0 ? "" : $sect->consult_hours_o);
            $total[5] += $sect->consult_hours_o;
            $t->setValue('tkz#' . $number, $sect->consult_hours_z == 0 ? "" : $sect->consult_hours_z);
            $total[6] += $sect->consult_hours_z;
            $t->setValue('ta#' . $number, $sect->attestation_form . ", " . $sect->attestation_hours);
            $total[7] += $sect->attestation_hours;


            //темы
            $theme_number = 0;
            foreach ($sect->themes as $theme) {
                $theme_number++;
                $number++;
                $t->setValue('tn#' . $number, $sec_number . "." . $theme_number);
                $t->setValue('tname#' . $number, $this->remove_nbsp($theme->name));
                $t->setValue('ttotal#' . $number, $theme->total_hours);
                $t->setValue('tlo#' . $number, $theme->lection_hours_o == 0 ? "" : $theme->lection_hours_o);
                $t->setValue('tlz#' . $number, $theme->lection_hours_z == 0 ? "" : $theme->lection_hours_z);
                $t->setValue('tpo#' . $number, $theme->practice_hours_o == 0 ? "" : $theme->practice_hours_o);
                $t->setValue('tpz#' . $number, $theme->practice_hours_z == 0 ? "" : $theme->practice_hours_z);
                $t->setValue('tko#' . $number, $theme->consult_hours_o == 0 ? "" : $theme->consult_hours_o);
                $t->setValue('tkz#' . $number, $theme->consult_hours_z == 0 ? "" : $theme->consult_hours_z);
                $t->setValue('ta#' . $number, $theme->attestation_hours == 0 ? "" : $theme->attestation_hours);
            }
        }
        $t->setValue('htotal', $total[0]);
        $t->setValue('hlo', $total[1]);
        $t->setValue('hlz', $total[2]);
        $t->setValue('hpo', $total[3]);
        $t->setValue('hpz', $total[4]);
        $t->setValue('hko', $total[5]);
        $t->setValue('hkz', $total[6]);
        $t->setValue('ha', $total[7]);

        $pathToSave = storage_path('ПрДПП_ПП_' . $dpp->abbreveation . '_ Учебный_план.docx');
        $t->saveAs($pathToSave);
        return response()->download($pathToSave);
    }

    public function export_learning_plan_pk(Dpp $dpp)
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

        $sv = $dpp->st_version;
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

        foreach ($sections as $sec_key => $section)
        {
            $sp = $sec_key+1;
            $table->addRow();
            $table->addCell(null)->addText($sp." ".$section->name,$boldStyle);
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
            foreach ($section->themes as $th_key => $theme)
            {
                $tp = $th_key + 1;
                $table->addRow();
                $table->addCell(null)->addText($sp.".".$tp." ".$theme->name,$normalStyle);
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

    public function clean_text($text)
    {
        $first_char = mb_substr($text, 0, 1);
        if (preg_match('/[^\pL0-9]/u', $first_char) == 1)
        {
            $text = trim($text,$first_char." ");
            $text = trim(preg_replace('/\t+/', '', $text));
        }

        $text = str_replace('&','&#38;',$text);
        $text = str_replace('<','&#60;',$text);
        $text = str_replace('>','&#62;',$text);
        return $text;
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


    public function export_lection_text(Dpp $dpp)
    {
        $tableStyle = array( 'borderColor' => '000000', 'borderSize'  => 1, 'cellMarginLeft' => 107.716535433,'cellMarginRight' => 107.716535433, 'cellMarginTop'  => 0,'cellMarginBottom'  => 0,'width'=> '100%');
        $tableWOBordersStyle = array('cellMargin'  => 50,'width'=> '100%');
        $tableNormalFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 12, 'bold' => false);
        $tableBoldFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 12, 'bold' => true);
        $cellHCenteredNoSpace = array('align' => 'center','lineHeight' => 1,'spaceAfter' => 0,'indentation'=> ['firstLine' => 0]);
        $cellNoSpace = array('spaceAfter' => 0,'lineHeight' => 1,'indentation'=> ['firstLine' => 0]);
        $firstRowStyle = array();
        $lection_mtos = Mto::where('is_base_for_lection',true)->where('dpp_id','=',$dpp->id)->get();
        $mergeArr = [];
        $t = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('/templates/make_lection.docx'));
        $t->setValue('dppName', $dpp->name);
        if ($dpp->dpp_type_id == 1) {$dppType = 'программы повышения квалификации';}else{$dppType = 'программы профессиональной переподготовки';}
        $t->setValue('dppType',$dppType);
        $t->setValue('year', date("Y"));
        $pathToSave = storage_path('/temp/start.docx');  $t->saveAs($pathToSave);array_push($mergeArr,$pathToSave);
        $sections = StructureSection::where('st_version_id',$dpp->st_version_id)->where('parent_id',null)->orderBy('position')->get();
        $section_id = null;
        foreach ($sections as $section)
        {
            $themes = StructureSection::where('parent_id',$section->id)->orderBy('position')->get();
            foreach ($themes as $theme)
            {
                if ($theme->lection_hours > 0)
                {
                    $t = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('/templates/make_lection2_text.docx'));
                    $inline = new \PhpOffice\PhpWord\Element\TextRun();
                    if ($section->id == $section_id)
                    {
                        $t->setComplexBlock('sectionName', $inline);
                    }else{
                        $t->setValue('sectionName',$section->position.". ".$section->name);
                        $section_id = $section->id;
                    }
                    $t->setValue('lectionName',$section->position.".".$theme->position.". ".$theme->name);

                    $t->setValue('lectionHours',$theme->lection_hours);

                    $pathToSave = storage_path('/temp/lection_'.$theme->id.'.docx');  $t->saveAs($pathToSave);array_push($mergeArr,$pathToSave);
                    $lection = Lection::where('section_id',$theme->id)->where('type','lec')->get()->first();
                    if ($lection)
                    {
                        $af = $lection->additional_files;
                        if ($af->count() > 0)
                        {
                            $t = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('/templates/make_dop_mat.docx'));
                            $t->cloneRow('dm_name', $af->count());
                            foreach ($af as $key=>$value)
                            {
                                $num = $key+1;
                                $t->setValue('dm_name#'.$num, $value->name);
                                $t->setValue('dm_url#'.$num, 'https://constructor.emiit.ru:8887/content/'.$lection->id.'/additional_files/'.$value->id.'/download');

                            }
                            $pathToSave = storage_path('/temp/lection_'.$theme->id.'_materials.docx');  $t->saveAs($pathToSave);array_push($mergeArr,$pathToSave);
                        }
                    }
                    $lectionContent = Lection::where('section_id',$theme->id)->where('type','lec')->get()->first();
                    if ($lectionContent && ($lectionContent->is_loaded))
                    {
                        $pathToSave = storage_path('app/lections/'.$lectionContent->id.'/Лекция.docm');array_push($mergeArr,$pathToSave);
                    }
                }

            }
        }
        $dm = new DocxMerge();
        $dm->merge( $mergeArr , storage_path('/temp/'.$dpp->abbreveation.'_текст_лекций.docx') );
        //$docxMerge = \Jupitern\Docx\DocxMerge::instance()
        // add array of files to merge
	    //->addFiles($mergeArr)
        // output filepath and pagebreak param
	    //->save(storage_path('/temp/'.$dpp->abbreveation.'_текст_лекций.docx'),true);
        return response()->download(storage_path('/temp/'.$dpp->abbreveation.'_текст_лекций.docx'));
    }


    public function test_template ()
    {
        // $source = storage_path('template.docm');
        // $phpWord = \PhpOffice\PhpWord\IOFactory::load($source);
        // try
        // {
        //     $a = $phpWord->getDocInfo()->getCustomPropertyValue("check");
        //     dd($a);
        // } catch(Exception $e)
        // {
        //     dd($e);
        // }

        //require "vendor/autoload.php";
        $dm = new DocxMerge();
        $dm->merge( [
            storage_path('/templates/shablon.docx'),
            storage_path('/template.docm')
        ], storage_path('/result.docx') );
        //dd("KEK");
        //\PhpOffice\PhpWord\Settings::setCompatibility(false);

       // $objReader = \PhpOffice\PhpWord\IOFactory::createReader('Word2007');
    //     $source = storage_path('templates/shablon.docx');
    //    // $phpWord = $objReader->load($source);
    //     $phpWord = \PhpOffice\PhpWord\IOFactory::load($source);
    //     dd($phpWord);
    //     $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
    //     $objWriter->save(storage_path('test_doc.docx'));
    //     return response()->download(storage_path('test_doc.docx'));
        //
       //$phpWord->getCompatibility()->setOoxmlVersion(15);
        //$spell = $phpWord->getSettings()->getProofState();
        //dd($spell);
        //$sections = $phpWord->getSections();
        //

        // $t = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('/templates/shablon.docx'));
        // dd($t->getMainPartName());
        // $pathToSave = storage_path('test_doc.docx');
        // $a = $t->saveAs($pathToSave);
        // dd($a);
        return response()->download(storage_path('/result.docx'));
    }


    public function content_template(Lection $content)
    {
        $t = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('/templates/template.docm'));
        //ТИТУЛ

        switch ($content->type)
        {
            case "lec": $pr = "Лекция"; $type = "Лекция.docm"; break;
            case "pr": $pr = "Практическое занятие"; $type = "Практическое занятие.docm"; break;
            case "lab": $pr = "Лабораторная работа"; $type = "Лабораторная работа.docm"; break;
        }
        $t->setValue('type', $pr);
        $t->setValue('name', $content->name);

        $t->setValue('type2', $pr);
        $t->setValue('name2', $content->name);
        $theme = StructureSection::find($content->section_id);
        $newFile = $theme->parent->position."_".$theme->position."_".$type;
        $pathToSave = storage_path('temp/'.$newFile);
        $t->saveAs($pathToSave);
        return response()->download(storage_path('temp/'.$newFile));
    }

    public function content_template2(Lection $content)
    {
        $t = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('/templates/template2.docm'));
        //ТИТУЛ

        switch ($content->type)
        {
            case "lec": $pr = "Лекция"; $type = "Лекция.docm"; break;
            case "pr": $pr = "Практическое занятие"; $type = "Практическое занятие.docm"; break;
            case "lab": $pr = "Лабораторная работа"; $type = "Лабораторная работа.docm"; break;
        }
        $t->setValue('type', $pr);
        $t->setValue('name', $content->name);

        $t->setValue('type2', $pr);
        $t->setValue('name2', $content->name);
        $theme = StructureSection::find($content->section_id);
        $newFile = $theme->parent->position."_".$theme->position."_".$type;
        $pathToSave = storage_path('temp/'.$newFile);
        $t->saveAs($pathToSave);
        return response()->download(storage_path('temp/'.$newFile));
    }


    public function content_download (Lection $content)
    {
        switch ($content->type)
        {
            case "lec": $type = "Лекция.docm"; break;
            case "pr": $type = "Практическое_занятие.docm"; break;
            case "lab": $type = "Лабораторная_работа.docm"; break;
        }
        $theme = StructureSection::find($content->section_id);
        $newFile = $theme->parent->position."_".$theme->position."_".$type;
        File::copy(storage_path('app/lections/'.$content->id.'/'.$type), storage_path('temp/'.$newFile));
        return response()->download(storage_path('temp/'.$newFile));
    }

    public function export_dpp_template(Dpp $dpp)
    {
        $t = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('/templates/dpp_template.docx'));
        $t->setValue('dppName', $dpp->name);
        $t->setValue('year', date("Y"));
        dd($t);
        $pathToSave = storage_path('dpps/'.$dpp->id.'ПрДпп.docx');
        $t->saveAs($pathToSave);
        return response()->download(storage_path('dpps/'.$dpp->id.'ПрДпп.docx'));

    }

    function node_attr($name,$value)
    {
     //  $res =
    }

    public function export_tests_xml(Dpp $dpp)
    {
        $questions = Question::whereHas('knowledge')->where('om_version_id',$dpp->om_version_id)->orderBy('knowledge_id','asc')->get();
        $dom = new \DOMDocument();
		$dom->encoding = 'utf-8';
		$dom->xmlVersion = '1.0';
		$dom->formatOutput = true;
	    $xml_file_name = 'qti.xml';

		$root = $dom->createElement('questestinterop');
        $dom->appendChild($root);
        $assessment = $dom->createElement('assessment');
        $assessment->setAttributeNode(new \DOMAttr('title', '1test'));
		$root->appendChild($assessment);
        $max_score = $dom->createElement('max_score',20);
        $assessment->appendChild($max_score);
        $assessmentcontrol = $dom->createElement('assessmentcontrol');
        $assessmentcontrol->setAttributeNode(new \DOMAttr('hintswitch', 'No'));
        $assessmentcontrol->setAttributeNode(new \DOMAttr('solutionswitch', 'No'));
        $assessmentcontrol->setAttributeNode(new \DOMAttr('lastattempt', 'Yes'));
        $assessmentcontrol->setAttributeNode(new \DOMAttr('feedbackswitch', 'Yes'));
        $assessment->appendChild($assessmentcontrol);
        $section = $dom->createElement('section');
        $section->setAttributeNode(new \DOMAttr('title', 'Раздел №1'));
        $selection_ordering = $dom->createElement('selection_ordering');
        $order = $dom->createElement('order');
        $order->setAttributeNode(new \DOMAttr('order_type', 'Sequential'));
        $selection_ordering->appendChild($order);
        $section->appendChild($selection_ordering);
        $sectioncontrol = $dom->createElement('sectioncontrol');
        $sectioncontrol->setAttributeNode(new \DOMAttr('hintswitch', 'No'));
        $sectioncontrol->setAttributeNode(new \DOMAttr('solutionswitch', 'No'));
        $sectioncontrol->setAttributeNode(new \DOMAttr('lastattempt', 'Yes'));
        $sectioncontrol->setAttributeNode(new \DOMAttr('feedbackswitch', 'Yes'));

        $section->appendChild($sectioncontrol);
        foreach ($questions as $question)
        {
            switch ($question->question_type_id)
            {

                case 1:
                    $item = $dom->createElement('item');
                    $item->setAttributeNode(new \DOMAttr('title', $question->text));
                    $item->setAttributeNode(new \DOMAttr('ident', 'constructor_'.$question->id));
                    $itemcontrol = $dom->createElement('itemcontrol');
                    $itemcontrol->setAttributeNode(new \DOMAttr('hintswitch', 'No'));
                    $itemcontrol->setAttributeNode(new \DOMAttr('solutionswitch', 'No'));
                    $itemcontrol->setAttributeNode(new \DOMAttr('lastattempt', 'Yes'));
                    $itemcontrol->setAttributeNode(new \DOMAttr('feedbackswitch', 'Yes'));
                    $item->appendChild($itemcontrol);
                    $presentation = $dom->createElement('presentation');
                    $material = $dom->createElement('material');
                    $mattext = $dom->createElement('mattext',$question->text);
                    $mattext->setAttributeNode(new \DOMAttr('texttype', 'text/plain'));
                    $material->appendChild($mattext);
                    $presentation->appendChild($material);
                    $response_lid = $dom->createElement('response_lid');
                    $response_lid->setAttributeNode(new \DOMAttr('rcardinality', 'Single'));
                    $response_lid->setAttributeNode(new \DOMAttr('ident', 'constructor_'.$question->id.'_resp'));
                    $render_choice = $dom->createElement('render_choice');
                    $render_choice->setAttributeNode(new \DOMAttr('shuffle', 'No'));
                    foreach ($question->single_choice_answers as $answer)
                    {
                        $response_label = $dom->createElement('response_label');
                        $response_label->setAttributeNode(new \DOMAttr('ws_right', $answer->is_right));
                        $response_label->setAttributeNode(new \DOMAttr('ident', $answer->id));
                        $material = $dom->createElement('material');
                        $mattext = $dom->createElement('mattext',htmlspecialchars($answer->text));
                        $mattext->setAttributeNode(new \DOMAttr('texttype', 'text/plain'));
                        $material->appendChild($mattext);
                        $response_label->appendChild($material);
                        $render_choice->appendChild($response_label);
                        if ($answer->is_right)
                        {
                            $right_answer = $answer->id;
                        }
                    }
                    $response_lid->appendChild($render_choice);
                    $presentation->appendChild($response_lid);
                    $item->appendChild($presentation);

                    $resppocessing = $dom->createElement('resprocessing');
                    $outcomes = $dom->createElement('outcomes');
                    $decvar = $dom->createElement('decvar');
                    $outcomes->appendChild($decvar);
                    $resppocessing->appendChild($outcomes);

                    $respcondition = $dom->createElement('respcondition');
                    $respcondition->setAttributeNode(new \DOMAttr('title', 'Correct'));
                    $conditionvar = $dom->createElement('conditionvar');
                    $varequal = $dom->createElement('varequal',$right_answer);
                    $varequal->setAttributeNode(new \DOMAttr('respident', 'constructor_'.$question->id.'_resp'));
                    $conditionvar->appendChild($varequal);
                    $respcondition->appendChild($conditionvar);
                    $setvar = $dom->createElement('setvar',1);
                    $respcondition->appendChild($setvar);
                    $resppocessing->appendChild($respcondition);

                    $respcondition = $dom->createElement('respcondition');
                    $respcondition->setAttributeNode(new \DOMAttr('title', 'Wrong'));
                    $conditionvar = $dom->createElement('conditionvar');
                    $other = $dom->createElement('other');
                    $conditionvar->appendChild($other);
                    $respcondition->appendChild($conditionvar);
                    $setvar = $dom->createElement('setvar',0);
                    $respcondition->appendChild($setvar);
                    $resppocessing->appendChild($respcondition);

                    $item->appendChild($resppocessing);
                    $section->appendChild($item);
                    break;
                case 2:
                    $item = $dom->createElement('item');
                    $item->setAttributeNode(new \DOMAttr('title', $question->text));
                    $item->setAttributeNode(new \DOMAttr('ident', 'constructor_'.$question->id));
                    $itemcontrol = $dom->createElement('itemcontrol');
                    $itemcontrol->setAttributeNode(new \DOMAttr('hintswitch', 'No'));
                    $itemcontrol->setAttributeNode(new \DOMAttr('solutionswitch', 'No'));
                    $itemcontrol->setAttributeNode(new \DOMAttr('lastattempt', 'Yes'));
                    $itemcontrol->setAttributeNode(new \DOMAttr('feedbackswitch', 'Yes'));
                    $item->appendChild($itemcontrol);
                    $presentation = $dom->createElement('presentation');
                    $material = $dom->createElement('material');
                    $mattext = $dom->createElement('mattext',$question->text);
                    $mattext->setAttributeNode(new \DOMAttr('texttype', 'text/plain'));
                    $material->appendChild($mattext);
                    $presentation->appendChild($material);
                    $response_lid = $dom->createElement('response_lid');
                    $response_lid->setAttributeNode(new \DOMAttr('rcardinality', 'Multiple'));
                    $response_lid->setAttributeNode(new \DOMAttr('ident', 'constructor_'.$question->id.'_resp'));
                    $render_choice = $dom->createElement('render_choice');
                    $render_choice->setAttributeNode(new \DOMAttr('shuffle', 'No'));

                    $rights = [];
                    $wrongs = [];
                    foreach ($question->multi_choice_answers as $answer)
                    {
                        $response_label = $dom->createElement('response_label');
                        $response_label->setAttributeNode(new \DOMAttr('ws_right', $answer->is_right));
                        $response_label->setAttributeNode(new \DOMAttr('ident', $answer->id));
                        $material = $dom->createElement('material');
                        $mattext = $dom->createElement('mattext',htmlspecialchars($answer->text));
                        $mattext->setAttributeNode(new \DOMAttr('texttype', 'text/plain'));
                        $material->appendChild($mattext);
                        $response_label->appendChild($material);
                        $render_choice->appendChild($response_label);
                        if ($answer->is_right)
                        {
                            array_push($rights,$answer->id);
                        }else{
                            array_push($wrongs,$answer->id);
                        }
                    }
                    $response_lid->appendChild($render_choice);
                    $presentation->appendChild($response_lid);
                    $item->appendChild($presentation);

                    $resppocessing = $dom->createElement('resprocessing');
                    $outcomes = $dom->createElement('outcomes');
                    $decvar = $dom->createElement('decvar');
                    $outcomes->appendChild($decvar);
                    $resppocessing->appendChild($outcomes);

                    $respcondition = $dom->createElement('respcondition');
                    $respcondition->setAttributeNode(new \DOMAttr('title', 'Correct'));
                    $conditionvar = $dom->createElement('conditionvar');
                    foreach ($rights as $r_a)
                    {
                        $varequal = $dom->createElement('varequal',$r_a);
                        $varequal->setAttributeNode(new \DOMAttr('respident', 'constructor_'.$question->id.'_resp'));
                        $conditionvar->appendChild($varequal);
                    }
                    foreach ($wrongs as $w_a)
                    {
                        $not = $dom->createElement('not');
                        $varequal = $dom->createElement('varequal',$w_a);
                        $varequal->setAttributeNode(new \DOMAttr('respident', 'constructor_'.$question->id.'_resp'));
                        $not->appendChild($varequal);
                        $conditionvar->appendChild($not);
                    }

                    $respcondition->appendChild($conditionvar);
                    $setvar = $dom->createElement('setvar',1);
                    $respcondition->appendChild($setvar);
                    $resppocessing->appendChild($respcondition);

                    $respcondition = $dom->createElement('respcondition');
                    $respcondition->setAttributeNode(new \DOMAttr('title', 'Wrong'));
                    $conditionvar = $dom->createElement('conditionvar');
                    $other = $dom->createElement('other');
                    $conditionvar->appendChild($other);
                    $respcondition->appendChild($conditionvar);
                    $setvar = $dom->createElement('setvar',0);
                    $respcondition->appendChild($setvar);
                    $resppocessing->appendChild($respcondition);

                    $item->appendChild($resppocessing);
                    $section->appendChild($item);
                    break;

                case 4:
                    $item = $dom->createElement('item');
                    $item->setAttributeNode(new \DOMAttr('title', $question->text));
                    $item->setAttributeNode(new \DOMAttr('ident', $question->id));

                    $itemcontrol = $dom->createElement('itemcontrol');
                    $itemcontrol->setAttributeNode(new \DOMAttr('hintswitch', 'No'));
                    $itemcontrol->setAttributeNode(new \DOMAttr('solutionswitch', 'No'));
                    $itemcontrol->setAttributeNode(new \DOMAttr('lastattempt', 'Yes'));
                    $itemcontrol->setAttributeNode(new \DOMAttr('feedbackswitch', 'Yes'));
                    $item->appendChild($itemcontrol);
                    $presentation = $dom->createElement('presentation');
                    $material = $dom->createElement('material');
                    $mattext = $dom->createElement('mattext',$question->text);
                    $mattext->setAttributeNode(new \DOMAttr('texttype', 'text/plain'));
                    $material->appendChild($mattext);
                    $presentation->appendChild($material);
                    $response_lid = $dom->createElement('response_lid');
                    $response_lid->setAttributeNode(new \DOMAttr('ident', $question->id.'_resp'));
                    $response_lid->setAttributeNode(new \DOMAttr('rcardinality', 'Ordered'));
                    $render_choice = $dom->createElement('render_choice');
                    $render_choice->setAttributeNode(new \DOMAttr('shuffle', 'Yes'));

                    $rights = [];
                    $wrongs = [];
                    foreach ($question->sequence_choice_answers as $answer)
                    {
                        $response_label = $dom->createElement('response_label');
                        $response_label->setAttributeNode(new \DOMAttr('ident', $question->id."_".$answer->id));
                        $response_label->setAttributeNode(new \DOMAttr('ws_right', 1));
                        $material = $dom->createElement('material');
                        $mattext = $dom->createElement('mattext',htmlspecialchars($answer->text));
                        $mattext->setAttributeNode(new \DOMAttr('texttype', 'text/plain'));
                        $material->appendChild($mattext);
                        $response_label->appendChild($material);

                        $render_choice->appendChild($response_label);
                        array_push($rights,$answer->id);

                    }
                    $response_lid->appendChild($render_choice);
                    $presentation->appendChild($response_lid);
                    $item->appendChild($presentation);

                    $resppocessing = $dom->createElement('resprocessing');
                    $outcomes = $dom->createElement('outcomes');
                    $decvar = $dom->createElement('decvar');
                    $outcomes->appendChild($decvar);
                    $resppocessing->appendChild($outcomes);

                    $respcondition = $dom->createElement('respcondition');
                    $respcondition->setAttributeNode(new \DOMAttr('title', 'Correct'));
                    $conditionvar = $dom->createElement('conditionvar');
                    foreach ($question->sequence_choice_answers as $answer)
                    {
                        $varequal = $dom->createElement('varequal', $question->id."_".$answer->id);
                        $varequal->setAttributeNode(new \DOMAttr('respident', $question->id.'_resp'));
                        $conditionvar->appendChild($varequal);
                    }
                    $respcondition->appendChild($conditionvar);
                    $setvar = $dom->createElement('setvar',1);
                    $respcondition->appendChild($setvar);
                    $resppocessing->appendChild($respcondition);

                    $respcondition = $dom->createElement('respcondition');
                    $respcondition->setAttributeNode(new \DOMAttr('title', 'Wrong'));
                    $conditionvar = $dom->createElement('conditionvar');
                    $other = $dom->createElement('other');
                    $conditionvar->appendChild($other);
                    $respcondition->appendChild($conditionvar);
                    $setvar = $dom->createElement('setvar',0);
                    $respcondition->appendChild($setvar);
                    $resppocessing->appendChild($respcondition);

                    $item->appendChild($resppocessing);
                    $section->appendChild($item);
                    break;
            }
        }
        $assessment->appendChild($section);
    /*
	$child_node_title = $dom->createElement('Title', 'The Campaign');

		$movie_node->appendChild($child_node_title);

		$child_node_year = $dom->createElement('Year', 2012);

		$movie_node->appendChild($child_node_year);

	$child_node_genre = $dom->createElement('Genre', 'The Campaign');

		$movie_node->appendChild($child_node_genre);

		$child_node_ratings = $dom->createElement('Ratings', 6.2);

		$movie_node->appendChild($child_node_ratings);

		$root->appendChild($movie_node);

		$dom->appendChild($root);
    */
	$dom->save($xml_file_name);
    return response()->download($xml_file_name);

    }

    public function export_part_tests_xml(Dpp $dpp, $position)
    {
        $dtp = $dpp->typology_parts()->where('position',$position)->get()->first();
        $knowledges = $dtp->knowledges()->pluck('id');
        $questions = Question::whereIn('knowledge_id', $knowledges)->get();
        //$questions = Question::whereHas('knowledge')->where('om_version_id',$dpp->om_version_id)->orderBy('knowledge_id','asc')->get();
        $dom = new \DOMDocument();
		$dom->encoding = 'utf-8';
		$dom->xmlVersion = '1.0';
		$dom->formatOutput = true;
	    $xml_file_name = 'qti_'.$position.'.xml';

		$root = $dom->createElement('questestinterop');
        $dom->appendChild($root);
        $assessment = $dom->createElement('assessment');
        $assessment->setAttributeNode(new \DOMAttr('title', '1test'));
		$root->appendChild($assessment);
        $max_score = $dom->createElement('max_score',10);
        $assessment->appendChild($max_score);
        $assessmentcontrol = $dom->createElement('assessmentcontrol');
        $assessmentcontrol->setAttributeNode(new \DOMAttr('hintswitch', 'No'));
        $assessmentcontrol->setAttributeNode(new \DOMAttr('solutionswitch', 'No'));
        $assessmentcontrol->setAttributeNode(new \DOMAttr('lastattempt', 'Yes'));
        $assessmentcontrol->setAttributeNode(new \DOMAttr('feedbackswitch', 'Yes'));
        $assessment->appendChild($assessmentcontrol);
        $section = $dom->createElement('section');
        $section->setAttributeNode(new \DOMAttr('title', 'Раздел №1'));
        $selection_ordering = $dom->createElement('selection_ordering');
        $order = $dom->createElement('order');
        $order->setAttributeNode(new \DOMAttr('order_type', 'Sequential'));
        $selection_ordering->appendChild($order);
        $section->appendChild($selection_ordering);
        $sectioncontrol = $dom->createElement('sectioncontrol');
        $sectioncontrol->setAttributeNode(new \DOMAttr('hintswitch', 'No'));
        $sectioncontrol->setAttributeNode(new \DOMAttr('solutionswitch', 'No'));
        $sectioncontrol->setAttributeNode(new \DOMAttr('lastattempt', 'Yes'));
        $sectioncontrol->setAttributeNode(new \DOMAttr('feedbackswitch', 'Yes'));

        $section->appendChild($sectioncontrol);
        foreach ($questions as $question)
        {
            switch ($question->question_type_id)
            {

                case 1:
                    $item = $dom->createElement('item');
                    $item->setAttributeNode(new \DOMAttr('title', $question->text));
                    $item->setAttributeNode(new \DOMAttr('ident', 'constructor_'.$question->id));
                    $itemcontrol = $dom->createElement('itemcontrol');
                    $itemcontrol->setAttributeNode(new \DOMAttr('hintswitch', 'No'));
                    $itemcontrol->setAttributeNode(new \DOMAttr('solutionswitch', 'No'));
                    $itemcontrol->setAttributeNode(new \DOMAttr('lastattempt', 'Yes'));
                    $itemcontrol->setAttributeNode(new \DOMAttr('feedbackswitch', 'Yes'));
                    $item->appendChild($itemcontrol);
                    $presentation = $dom->createElement('presentation');
                    $material = $dom->createElement('material');
                    $mattext = $dom->createElement('mattext',$question->text);
                    $mattext->setAttributeNode(new \DOMAttr('texttype', 'text/plain'));
                    $material->appendChild($mattext);
                    $presentation->appendChild($material);
                    $response_lid = $dom->createElement('response_lid');
                    $response_lid->setAttributeNode(new \DOMAttr('rcardinality', 'Single'));
                    $response_lid->setAttributeNode(new \DOMAttr('ident', 'constructor_'.$question->id.'_resp'));
                    $render_choice = $dom->createElement('render_choice');
                    $render_choice->setAttributeNode(new \DOMAttr('shuffle', 'No'));
                    foreach ($question->single_choice_answers as $answer)
                    {
                        $response_label = $dom->createElement('response_label');
                        $response_label->setAttributeNode(new \DOMAttr('ws_right', $answer->is_right));
                        $response_label->setAttributeNode(new \DOMAttr('ident', $answer->id));
                        $material = $dom->createElement('material');
                        $mattext = $dom->createElement('mattext',htmlspecialchars($answer->text));
                        $mattext->setAttributeNode(new \DOMAttr('texttype', 'text/plain'));
                        $material->appendChild($mattext);
                        $response_label->appendChild($material);
                        $render_choice->appendChild($response_label);
                        if ($answer->is_right)
                        {
                            $right_answer = $answer->id;
                        }
                    }
                    $response_lid->appendChild($render_choice);
                    $presentation->appendChild($response_lid);
                    $item->appendChild($presentation);

                    $resppocessing = $dom->createElement('resprocessing');
                    $outcomes = $dom->createElement('outcomes');
                    $decvar = $dom->createElement('decvar');
                    $outcomes->appendChild($decvar);
                    $resppocessing->appendChild($outcomes);

                    $respcondition = $dom->createElement('respcondition');
                    $respcondition->setAttributeNode(new \DOMAttr('title', 'Correct'));
                    $conditionvar = $dom->createElement('conditionvar');
                    $varequal = $dom->createElement('varequal',$right_answer);
                    $varequal->setAttributeNode(new \DOMAttr('respident', 'constructor_'.$question->id.'_resp'));
                    $conditionvar->appendChild($varequal);
                    $respcondition->appendChild($conditionvar);
                    $setvar = $dom->createElement('setvar',1);
                    $respcondition->appendChild($setvar);
                    $resppocessing->appendChild($respcondition);

                    $respcondition = $dom->createElement('respcondition');
                    $respcondition->setAttributeNode(new \DOMAttr('title', 'Wrong'));
                    $conditionvar = $dom->createElement('conditionvar');
                    $other = $dom->createElement('other');
                    $conditionvar->appendChild($other);
                    $respcondition->appendChild($conditionvar);
                    $setvar = $dom->createElement('setvar',0);
                    $respcondition->appendChild($setvar);
                    $resppocessing->appendChild($respcondition);

                    $item->appendChild($resppocessing);
                    $section->appendChild($item);
                    break;
                case 2:
                    $item = $dom->createElement('item');
                    $item->setAttributeNode(new \DOMAttr('title', $question->text));
                    $item->setAttributeNode(new \DOMAttr('ident', 'constructor_'.$question->id));
                    $itemcontrol = $dom->createElement('itemcontrol');
                    $itemcontrol->setAttributeNode(new \DOMAttr('hintswitch', 'No'));
                    $itemcontrol->setAttributeNode(new \DOMAttr('solutionswitch', 'No'));
                    $itemcontrol->setAttributeNode(new \DOMAttr('lastattempt', 'Yes'));
                    $itemcontrol->setAttributeNode(new \DOMAttr('feedbackswitch', 'Yes'));
                    $item->appendChild($itemcontrol);
                    $presentation = $dom->createElement('presentation');
                    $material = $dom->createElement('material');
                    $mattext = $dom->createElement('mattext',$question->text);
                    $mattext->setAttributeNode(new \DOMAttr('texttype', 'text/plain'));
                    $material->appendChild($mattext);
                    $presentation->appendChild($material);
                    $response_lid = $dom->createElement('response_lid');
                    $response_lid->setAttributeNode(new \DOMAttr('rcardinality', 'Multiple'));
                    $response_lid->setAttributeNode(new \DOMAttr('ident', 'constructor_'.$question->id.'_resp'));
                    $render_choice = $dom->createElement('render_choice');
                    $render_choice->setAttributeNode(new \DOMAttr('shuffle', 'No'));

                    $rights = [];
                    $wrongs = [];
                    foreach ($question->multi_choice_answers as $answer)
                    {
                        $response_label = $dom->createElement('response_label');
                        $response_label->setAttributeNode(new \DOMAttr('ws_right', $answer->is_right));
                        $response_label->setAttributeNode(new \DOMAttr('ident', $answer->id));
                        $material = $dom->createElement('material');
                        $mattext = $dom->createElement('mattext',htmlspecialchars($answer->text));
                        $mattext->setAttributeNode(new \DOMAttr('texttype', 'text/plain'));
                        $material->appendChild($mattext);
                        $response_label->appendChild($material);
                        $render_choice->appendChild($response_label);
                        if ($answer->is_right)
                        {
                            array_push($rights,$answer->id);
                        }else{
                            array_push($wrongs,$answer->id);
                        }
                    }
                    $response_lid->appendChild($render_choice);
                    $presentation->appendChild($response_lid);
                    $item->appendChild($presentation);

                    $resppocessing = $dom->createElement('resprocessing');
                    $outcomes = $dom->createElement('outcomes');
                    $decvar = $dom->createElement('decvar');
                    $outcomes->appendChild($decvar);
                    $resppocessing->appendChild($outcomes);

                    $respcondition = $dom->createElement('respcondition');
                    $respcondition->setAttributeNode(new \DOMAttr('title', 'Correct'));
                    $conditionvar = $dom->createElement('conditionvar');
                    foreach ($rights as $r_a)
                    {
                        $varequal = $dom->createElement('varequal',$r_a);
                        $varequal->setAttributeNode(new \DOMAttr('respident', 'constructor_'.$question->id.'_resp'));
                        $conditionvar->appendChild($varequal);
                    }
                    foreach ($wrongs as $w_a)
                    {
                        $not = $dom->createElement('not');
                        $varequal = $dom->createElement('varequal',$w_a);
                        $varequal->setAttributeNode(new \DOMAttr('respident', 'constructor_'.$question->id.'_resp'));
                        $not->appendChild($varequal);
                        $conditionvar->appendChild($not);
                    }

                    $respcondition->appendChild($conditionvar);
                    $setvar = $dom->createElement('setvar',1);
                    $respcondition->appendChild($setvar);
                    $resppocessing->appendChild($respcondition);

                    $respcondition = $dom->createElement('respcondition');
                    $respcondition->setAttributeNode(new \DOMAttr('title', 'Wrong'));
                    $conditionvar = $dom->createElement('conditionvar');
                    $other = $dom->createElement('other');
                    $conditionvar->appendChild($other);
                    $respcondition->appendChild($conditionvar);
                    $setvar = $dom->createElement('setvar',0);
                    $respcondition->appendChild($setvar);
                    $resppocessing->appendChild($respcondition);

                    $item->appendChild($resppocessing);
                    $section->appendChild($item);
                    break;

                case 4:
                    $item = $dom->createElement('item');
                    $item->setAttributeNode(new \DOMAttr('title', $question->text));
                    $item->setAttributeNode(new \DOMAttr('ident', $question->id));

                    $itemcontrol = $dom->createElement('itemcontrol');
                    $itemcontrol->setAttributeNode(new \DOMAttr('hintswitch', 'No'));
                    $itemcontrol->setAttributeNode(new \DOMAttr('solutionswitch', 'No'));
                    $itemcontrol->setAttributeNode(new \DOMAttr('lastattempt', 'Yes'));
                    $itemcontrol->setAttributeNode(new \DOMAttr('feedbackswitch', 'Yes'));
                    $item->appendChild($itemcontrol);
                    $presentation = $dom->createElement('presentation');
                    $material = $dom->createElement('material');
                    $mattext = $dom->createElement('mattext',$question->text);
                    $mattext->setAttributeNode(new \DOMAttr('texttype', 'text/plain'));
                    $material->appendChild($mattext);
                    $presentation->appendChild($material);
                    $response_lid = $dom->createElement('response_lid');
                    $response_lid->setAttributeNode(new \DOMAttr('ident', $question->id.'_resp'));
                    $response_lid->setAttributeNode(new \DOMAttr('rcardinality', 'Ordered'));
                    $render_choice = $dom->createElement('render_choice');
                    $render_choice->setAttributeNode(new \DOMAttr('shuffle', 'Yes'));

                    $rights = [];
                    $wrongs = [];
                    foreach ($question->sequence_choice_answers as $answer)
                    {
                        $response_label = $dom->createElement('response_label');
                        $response_label->setAttributeNode(new \DOMAttr('ident', $question->id."_".$answer->id));
                        $response_label->setAttributeNode(new \DOMAttr('ws_right', 1));
                        $material = $dom->createElement('material');
                        $mattext = $dom->createElement('mattext',htmlspecialchars($answer->text));
                        $mattext->setAttributeNode(new \DOMAttr('texttype', 'text/plain'));
                        $material->appendChild($mattext);
                        $response_label->appendChild($material);

                        $render_choice->appendChild($response_label);
                        array_push($rights,$answer->id);

                    }
                    $response_lid->appendChild($render_choice);
                    $presentation->appendChild($response_lid);
                    $item->appendChild($presentation);

                    $resppocessing = $dom->createElement('resprocessing');
                    $outcomes = $dom->createElement('outcomes');
                    $decvar = $dom->createElement('decvar');
                    $outcomes->appendChild($decvar);
                    $resppocessing->appendChild($outcomes);

                    $respcondition = $dom->createElement('respcondition');
                    $respcondition->setAttributeNode(new \DOMAttr('title', 'Correct'));
                    $conditionvar = $dom->createElement('conditionvar');
                    foreach ($question->sequence_choice_answers as $answer)
                    {
                        $varequal = $dom->createElement('varequal', $question->id."_".$answer->id);
                        $varequal->setAttributeNode(new \DOMAttr('respident', $question->id.'_resp'));
                        $conditionvar->appendChild($varequal);
                    }
                    $respcondition->appendChild($conditionvar);
                    $setvar = $dom->createElement('setvar',1);
                    $respcondition->appendChild($setvar);
                    $resppocessing->appendChild($respcondition);

                    $respcondition = $dom->createElement('respcondition');
                    $respcondition->setAttributeNode(new \DOMAttr('title', 'Wrong'));
                    $conditionvar = $dom->createElement('conditionvar');
                    $other = $dom->createElement('other');
                    $conditionvar->appendChild($other);
                    $respcondition->appendChild($conditionvar);
                    $setvar = $dom->createElement('setvar',0);
                    $respcondition->appendChild($setvar);
                    $resppocessing->appendChild($respcondition);

                    $item->appendChild($resppocessing);
                    $section->appendChild($item);
                    break;
            }
        }
        $assessment->appendChild($section);

	$dom->save($xml_file_name);
    return response()->download($xml_file_name);

    }

    public function remove_nbsp($text)
    {
        $text = str_replace("&nbsp;"," ",htmlentities($text));
        $text = str_replace("&ensp;","",$text);
        $text = str_replace(" &quot;"," «",$text);
        $text = str_replace("&quot;","»",$text);
        $text = str_replace("&laquo;","«",$text);
        $text = str_replace("&raquo;","»",$text);

        return $text;
    }
}
