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
use App\Signatory;
use App\Nsi;
use App\Task;
use \PhpOffice\PhpWord\PhpWord;
use \PhpOffice\PhpWord\Style\Language;
use Illuminate\Support\Facades\Storage;
use \PhpOffice\PhpWord\Element\TextRun;
use File;

use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;


class ExportOMController extends Controller
{
    public function export_om (Dpp $dpp)
    {
        if ((!is_null($dpp->category)) && ($dpp->category->name == 'РОСДОРНИИ' ))
        {
            return $this->export_om_rdn($dpp);
        }
        $t = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('/templates/make_om_miit.docx'));
        \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true);

        //ТИТУЛ

        $t->setValue('dppName', $dpp->name);
        $t->setValue('year', $dpp->year);

        //умений и навыков
        if ($dpp->skills()->count() > 0) {
            $phrase1 = 'умений и навыков';
            $phrase2 = 'yнавык';
        }else{
            $phrase1 = 'умений';
            $phrase2 = 'умение';
        }
        $t->setValue('skill_ability', $phrase1);

        //ТАБЛИЦА 2
        $zv = $dpp->zun_version_id;
        //ПЛАНИРУЕМЫЕ РЕЗУЛЬТАТЫ ОСВОЕНИЯ
        $zv = $dpp->zun_version_id;
        $boldFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 12, 'bold' => true);
        $normalFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 12, 'bold' => false);
        $competences = Competence::where('zun_version_id','=',$zv)->get();
        if (count($competences) > 1) {$add=1;}else{$add=0;}
        $t->cloneRow('competence', count($competences)+$add);
        $number = 1;
        foreach ($competences as $competence)
        {
            $t->setValue('competence#'.$number, $competence->name);
            $skills = Skill::where('competence_id',$competence->id)->get()->pluck('name')->toArray();
            $sk_ids = Skill::where('competence_id',$competence->id)->get()->pluck('id')->toArray();
            $text = new TextRun();
            if (count($skills) > 0)
            {
                $breakStyle = array('lineHeight' => 1.5,'spaceAfter' => 6);
                $total = count($skills);
                $text->addText('Навыки: ', $boldFont);
                $text->addTextBreak();
                foreach ($skills as $key=>$value)
                {
                    $text->addText($value,$normalFont);
                    if ($key==$total-1) {$sep='.';}else{$sep=',';}
                    $text->addText($sep,$normalFont,$breakStyle);
                    if ($key!=$total-1)
                    {
                        $text->addTextBreak(1,$normalFont);
                    }
                }
            }
            $t->setComplexValue('skills#'.$number, $text);


            $abilities = Ability::where('competence_id',$competence->id)->get();
            if ($abilities->count() == 0)
            {
                $abilities = Ability::whereIn('skill_id',$sk_ids)->get();
            }

            $ab_ids = $abilities->pluck('id')->toArray();
            $text = new TextRun();
            if (count($abilities) > 0)
            {
                $breakStyle = array('lineHeight' => 1.5,'spaceAfter' => 6);

                $ab_names = $abilities->pluck('name')->toArray();
                $total = count($abilities);
                $text->addText('Умения: ', $boldFont);
                $text->addTextBreak();
                foreach ($ab_names as $key=>$value)
                {
                    $text->addText($value,$normalFont);
                    if ($key==$total-1) {$sep='.';}else{$sep=',';}
                    $text->addText($sep,$normalFont,$breakStyle);
                    if ($key!=$total-1)
                    {
                        $text->addTextBreak(1,$normalFont);
                    }
                }
            }
            $t->setComplexValue('abilities#'.$number, $text);

            $knowledges = Knowledge::whereIn('ability_id',$ab_ids)->get();
            $knowledge_names = $knowledges->pluck('name')->toArray();
            if ($dpp->competences()->count() == 1)
            {
                $th_knowledges = Knowledge::where('zun_version_id',$zv)->where('is_through',true)->orderBy('position','asc')->get();
                $th_names = $th_knowledges->pluck('name')->toArray();
                foreach ($th_names as $th_name)
                {
                    array_push($knowledge_names,$th_name);
                }
            }

            $text = new TextRun();
            if (count($knowledges) > 0)
            {
                $breakStyle = array('lineHeight' => 1.5,'spaceAfter' => 6);
                $total = count($knowledge_names);
                $text->addText('Знания: ', $boldFont);
                $text->addTextBreak();
                foreach ($knowledge_names as $key=>$value)
                {
                    $text->addText($value,$normalFont);
                    if ($key==$total-1) {$sep='.';}else{$sep=',';}
                    $text->addText($sep,$normalFont,$breakStyle);
                    if ($key!=$total-1)
                    {
                        $text->addTextBreak(1,$normalFont);
                    }
                }
            }

            $t->setComplexValue('knowledges#'.$number, $text);
            $number++;
        }
        if ($dpp->competences()->count() > 1)
        {
            $th_knowledges = Knowledge::where('zun_version_id',$zv)->where('is_through',true)->orderBy('position','asc')->get();
            $text = new TextRun();
            if (count($th_knowledges) > 0)
            {
                $competences = $dpp->competences();
                //$text->addText(implode(', ',$competences->pluck('name')->toArray()).".", $normalFont);
                $text->addText('Все компетенции, перечисленные выше', $normalFont);
                $t->setComplexValue('competence#'.$number, $text);

                $th_names = $th_knowledges->pluck('name')->toArray();
                $text = new TextRun();
                $breakStyle = array('lineHeight' => 1.5,'spaceAfter' => 6);
                $total = count($th_names);
                $text->addText('Сквозные знания: ', $boldFont);
                $text->addTextBreak();
                foreach ($th_names as $key=>$value)
                {
                    $text->addText($value,$normalFont);
                    if ($key==$total-1) {$sep='.';}else{$sep=',';}
                    $text->addText($sep,$normalFont,$breakStyle);
                    if ($key!=$total-1)
                    {
                        $text->addTextBreak(1,$normalFont);
                    }
                }
                $t->setComplexValue('knowledges#'.$number, $text);
                $text = new TextRun();
                $t->setComplexValue('skills#'.$number, $text);
                $t->setComplexValue('abilities#'.$number, $text);
            }
        }

        // ТАБЛИЦА 3

        $knowledges = Knowledge::where('zun_version_id','=',$zv)->get();
        $t->cloneRow('knowledge', $knowledges->count());
        $number = 0;

        foreach ($knowledges as  $index => $knowledge)
        {
            $i = $index + 1;
            $t->setValue('knowledge#'.$i, $knowledge->name);

          //  $sct = new \PhpOffice\PhpWord\Element\Section();
            //qtn
            $qtn = new \PhpOffice\PhpWord\Element\TextRun();
            $types_count = 0;

            $questions = $knowledge->questions;
            $type2 = $questions->where('question_type_id',2);
            $type = $questions->where('question_type_id',1)->union($type2);
            if ($type->count() > 0)
            {
                $qtn->addText('Задания с выбором ответа: ');
                $arr = [];
                foreach ($type as $value) {$number++; array_push($arr,$number);}
                $qtn->addText(implode(', ',$arr));
                $types_count++;
            }
            $type = $questions->where('question_type_id',3);
            if ($type->count() > 0)
            {
                if ($types_count > 0) {
                  $qtn->addTextBreak();
                }
                $qtn->addText('Задания с открытым ответом: ');
                $arr = [];
                foreach ($type as $value) {$number++; array_push($arr,$number);}
                $qtn->addText(implode(', ',$arr));
                $types_count++;
            }
            $type = $questions->where('question_type_id',4);
            if ($type->count() > 0)
            {
                if ($types_count > 0) {
                    $qtn->addTextBreak();
                }
                $qtn->addText('Задания на установление последовательности: ');
                $arr = [];
                foreach ($type as $value) {$number++; array_push($arr,$number);}
                $qtn->addText(implode(', ',$arr));
                $types_count++;
            }
            $type = $questions->where('question_type_id',5);

            if ($type->count() > 0)
            {
                if ($types_count > 0) {
                  $qtn->addTextBreak();
                }
                $qtn->addText('Задания на установление соответствия: ');
                $arr = [];
                foreach ($type as $value) {$number++; array_push($arr,$number);}
                $qtn->addText(implode(', ',$arr));
                $types_count++;
            }

            $t->setComplexValue('qtn#'.$i, $qtn);
        }

        $ov = OmVersion::find($dpp->om_version_id);
        //$questions = $ov->questions;
        $questions = Question::where('om_version_id',$dpp->om_version_id)
        ->join('knowledge', 'questions.knowledge_id', '=', 'knowledge.id')
        ->where('knowledge.deleted_at',null)
        ->get();
        $type2 = $questions->where('question_type_id',2);
        $type = $questions->where('question_type_id',1)->union($type2);
        $t->setValue('QChooseCount', $type->count());
        $type = $questions->where('question_type_id',3);
        $t->setValue('QFreeCount', $type->count());
        $type = $questions->where('question_type_id',4);
        $t->setValue('QSequenceCount', $type->count());
        $type = $questions->where('question_type_id',5);
        $t->setValue('QAccordanceCount', $type->count());

        $t->setValue('testHours', round($ov->test_questions * 1.5 / 45,2));


        //ТАБЛИЦА 4
        $rows = 0;
        $task_time = 0;
        $skills = Skill::where('zun_version_id','=',$zv)->get();
        $rows += $skills->count();
        $abilities = Ability::where('zun_version_id','=',$zv)->get();
        $rows += $abilities->count();
        $t->cloneRow('sa', $rows);

        $current_row = 0;
        foreach ($skills as $skill)
        {
            $id = $skill->id;
            $skill_tasks = Task::whereHas('subjects', function ($query) use ($id) {
                return $query->where('skill_id', '=', $id);
            })->distinct()->get();
            $current_row++;
                $positions = $skill_tasks->pluck('position')->toArray();
                $positions = implode(", ",$positions);
                $t->setValue('sa#'.$current_row, $skill->name);
                $t->setValue('saTaskType#'.$current_row,"задание на применение умений и навыков в модельных условиях");
                $t->setValue('saTaskNumber#'.$current_row,$positions);
        }

        foreach ($abilities as $ability)
        {
            $id = $ability->id;
            $ability_tasks = Task::whereHas('subjects', function ($query) use ($id) {
                return $query->where('ability_id', '=', $id);
            })->distinct()->get();
            $current_row++;
            $positions = $ability_tasks->pluck('position')->toArray();
            $positions = implode(", ",$positions);
            $t->setValue('sa#'.$current_row, $ability->name);
            $t->setValue('saTaskType#'.$current_row,"задание на применение умений в модельных условиях");
            $t->setValue('saTaskNumber#'.$current_row,$positions);
        }

        $task_time = $ov->tasks->sum('time');
        $t->setValue('taskHours',round($task_time / 45,2));

        //taskIAHours
        $required_tasks_time = $ov->tasks()->where('required',true)->sum('time');
        $optional_tasks_avg_time = $ov->tasks()->where('required',false)->avg('time');
        $tasks_time = $required_tasks_time + $optional_tasks_avg_time * $ov->optional_tasks;
        $t->setValue('taskIAHours',round($tasks_time / 45,2));

        try {
            $t->setComplexBlock('knowledge_mto',  $this->get_knowledge_mto_table($dpp));
        } catch (Exception $e) {
            dd('Ошибка в МТО знаний');
        }

        try {
            $t->setComplexBlock('nsi', $this->get_nsi_table($dpp));
        } catch (Exception $e) {
            dd('Ошибка в НСИ');
        }

        $knowledges = Knowledge::where('zun_version_id','=',$zv)->get();
        $questions = Question::where('om_version_id',$dpp->om_version_id)->join('knowledge', 'questions.knowledge_id', '=', 'knowledge.id')
        ->where('knowledge.deleted_at',null)
        ->get();
        $t->cloneBlock('question_block', $questions->count(), true, true);
        $t->cloneRow('rignt_answers', $questions->count());
        $number = 0;
        $symbols = ['a','б','в','г','д','е','ж','з','и','к','л','м','н','о','п','р','с','у','ф','х','ц','ч','ш','щ','э','ю','я'];
        $normalFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 14, 'bold' => false);
        $boldFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 14, 'bold' => true);
        $normalParagraph = array('alignment' => 'both','lineHeight' => 1.5,'spaceAfter' => 0);
        $normalParagraphLH1 = array('alignment' => 'both','lineHeight' => 1,'spaceAfter' => 0);
        $normalParagraphLH1Left = array('alignment' => 'both','lineHeight' => 1,'spaceAfter' => 0);
        $tableStyle = array( 'borderColor' => '000000', 'borderSize'  => 1, 'cellMarginLeft' => 107.716535433,'cellMarginRight' => 107.716535433, 'cellMarginTop'  => 0,'cellMarginBottom'  => 0,'width'=> '100%');
        $tableWOBordersStyle = array('cellMargin'  => 50,'width'=> '100%');
        $tableNormalFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 12, 'bold' => false);
        $tableBoldFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 12, 'bold' => true);
        $cellHCenteredNoSpace = array('align' => 'center','lineHeight' => 1,'spaceAfter' => 0,'indentation'=> ['firstLine' => 0]);
        $cellNoSpace = array('spaceAfter' => 0,'lineHeight' => 1,'indentation'=> ['firstLine' => 0]);
        $firstRowStyle = array();
        foreach ($knowledges as  $index => $knowledge)
        {
            $questions = $knowledge->questions;
            $type2 = $questions->where('question_type_id',2);
            $type = $questions->where('question_type_id',1)->union($type2);
            foreach ($type as $question)
            {
                $number++;
                $rigntAnswersArr = [];
                $t->setValue('text#'.$number, $number." ".$this->clean_text($question->text));
                if (!is_null($question->image))
                {
                    $result = $this->get_image_width_height($question->image);
                    $t->setImageValue('question_image#'.$number, array('path' => $result['path'], 'width' =>$result['width'].'px', 'height' => $result['height'].'px'));
                }else{
                    $blank = new \PhpOffice\PhpWord\Element\TextRun();
                    $blank->addText('_', array('size' => 0,'color' => 'FFFFFF'));
                    $t->setComplexValue('question_image#'.$number, $blank);
                }
                $t->setValue('type#'.$number, $question->type->name);
                if ($question->question_type_id == 1)
                {
                    $table  = new \PhpOffice\PhpWord\Element\Table();
                    $answers = $question->single_choice_answers;
                    foreach ($answers as $key => $answer)
                    {
                        $text = $this->clean_text($answer->text);

                        if ($key != $answers->count()-1) {$end=';';}else{$end='.';}
                        $table->addRow(null);
                        $table->addCell(9530)->addText($symbols[$key].") ".$text.$end,$normalFont,$normalParagraphLH1Left);
                        if (!is_null($answer->image))
                        {
                            $table->addRow(null);
                            $table->addCell(9530)->addText('${image_identifier_'.$answer->id.':300:300}');
                        }
                        if ($answer->is_right == 1) {array_push($rigntAnswersArr,$symbols[$key]);}
                    }
                     $t->setComplexBlock('answers#'.$number, $table);
                     foreach ($answers as $answer)
                     {
                        if (!is_null($answer->image))
                        {
                            $result = $this->get_image_width_height($answer->image);
                            $t->setImageValue('image_identifier_'.$answer->id, array('path' => $result['path'], 'width' =>$result['width'].'px', 'height' => $result['height'].'px'));
                        }
                     }
                     $t->setValue('rignt_answers#'.$number, $number);
                     $t->setValue('rignt_answers_text#'.$number, implode(", ",$rigntAnswersArr));
                }
                if ($question->question_type_id == 2)
                {
                    $table  = new \PhpOffice\PhpWord\Element\Table();
                    $answers = $question->multi_choice_answers;
                    foreach ($answers as $key => $answer)
                    {
                        $text = $this->clean_text($answer->text);
                        if ($key != $answers->count()-1) {$end=';';}else{$end='.';}
                        $table->addRow(null);
                        $table->addCell(9530)->addText($symbols[$key].") ".$text.$end,$normalFont,$normalParagraphLH1Left);
                        if (!is_null($answer->image))
                        {
                            $table->addRow(null);
                            $table->addCell(9530)->addText('${image_identifier_'.$answer->id.'}');
                        }
                        if ($answer->is_right == 1) {array_push($rigntAnswersArr,$symbols[$key]);}
                    }
                    //
                    if (($number <= 200) || ($number >= 300)) {
                        $t->setComplexBlock('answers#'.$number, $table);
                    }


                     $t->setValue('rignt_answers#'.$number, $number);
                     $t->setValue('rignt_answers_text#'.$number, implode(",",$rigntAnswersArr));

                     foreach ($answers as $answer)
                     {
                        if (!is_null($answer->image))
                        {
                            $result = $this->get_image_width_height($answer->image);
                            $t->setImageValue('image_identifier_'.$answer->id, array('path' => $result['path'], 'width' =>$result['width'].'px', 'height' => $result['height'].'px'));
                        }
                     }
                }
            }

            $type = $questions->where('question_type_id',3);
            foreach ($type as $question)
            {
                $number++;
                $rigntAnswersArr = [];
                $answers = $question->free_choice_answers;
                $t->setValue('text#'.$number, $number." ".$this->clean_text($question->text));
                if (!is_null($question->image))
                {
                    $result = $this->get_image_width_height($question->image);
                    $t->setImageValue('question_image#'.$number, array('path' => $result['path'], 'width' =>$result['width'].'px', 'height' => $result['height'].'px'));
                }else{
                    $blank = new \PhpOffice\PhpWord\Element\TextRun();
                    $blank->addText('_', array('size' => 0,'color' => 'FFFFFF'));
                    $t->setComplexValue('question_image#'.$number, $blank);
                }

                $t->setValue('type#'.$number, $question->type->name);
                $t->setValue('answers#'.$number, " ");
                foreach ($answers as $key => $answer)
                {
                    array_push($rigntAnswersArr,$answer->text);
                }
                $t->setValue('rignt_answers#'.$number, $number);
                $t->setValue('rignt_answers_text#'.$number, implode("; ",$rigntAnswersArr));
            }

            $type = $questions->where('question_type_id',4);
            foreach ($type as $question)
            {
                $number++;
                $rigntAnswersArr = [];
                $t->setValue('text#'.$number, $number." ".$this->clean_text($question->text));
                if (!is_null($question->image))
                {
                    $result = $this->get_image_width_height($question->image);
                    $t->setImageValue('question_image#'.$number, array('path' => $result['path'], 'width' =>$result['width'].'px', 'height' => $result['height'].'px'));
                }else{
                    $blank = new \PhpOffice\PhpWord\Element\TextRun();
                    $blank->addText('_', array('size' => 0,'color' => 'FFFFFF'));
                    $t->setComplexValue('question_image#'.$number, $blank);
                }
                $t->setValue('type#'.$number, $question->type->name);
                $table  = new \PhpOffice\PhpWord\Element\Table();
                $answers = $question->sequence_choice_answers;
                foreach ($answers as $key => $answer)
                {
                    $n = $key+1;
                    $text = $this->clean_text($answer->text);
                    if ($key != $answers->count()-1) {$end=';';}else{$end='.';}
                    $table->addRow(null);
                    $table->addCell(9530)->addText($n." ".$text.$end,$normalFont,$normalParagraphLH1Left);
                    if (!is_null($answer->image))
                    {
                        $table->addRow(null);
                        $table->addCell(9530)->addText('${image_identifier_'.$answer->id.'}');
                    }
                    array_push($rigntAnswersArr,$n);
                }

                $t->setComplexBlock('answers#'.$number, $table);
                $t->setValue('rignt_answers#'.$number, $number);
                $t->setValue('rignt_answers_text#'.$number, implode(",",$rigntAnswersArr));

                foreach ($answers as $answer)
                {
                    if (!is_null($answer->image))
                    {
                        $result = $this->get_image_width_height($answer->image);
                        $t->setImageValue('image_identifier_'.$answer->id, array('path' => $result['path'], 'width' =>$result['width'].'px', 'height' => $result['height'].'px'));
                    }
                }
            }

            $type = $questions->where('question_type_id',5);
            foreach ($type as $question)
            {
                $number++;
                $rigntAnswersArr = [];
                $t->setValue('text#'.$number, $number." ".$this->clean_text($question->text));
                if (!is_null($question->image))
                {
                    $result = $this->get_image_width_height($question->image);
                    $t->setImageValue('question_image#'.$number, array('path' => $result['path'], 'width' =>$result['width'].'px', 'height' => $result['height'].'px'));
                }else{
                    $blank = new \PhpOffice\PhpWord\Element\TextRun();
                    $blank->addText('_', array('size' => 0,'color' => 'FFFFFF'));
                    $t->setComplexValue('question_image#'.$number, $blank);
                }
                $t->setValue('type#'.$number, $question->type->name);
                $table  = new \PhpOffice\PhpWord\Element\Table($tableStyle);
                $table->addRow(null,array('tblHeader' => true));
                $table->addCell(4765)->addText("Колонка 1",$tableBoldFont,$cellHCenteredNoSpace);
                $table->addCell(4765)->addText("Колонка 2",$tableBoldFont,$cellHCenteredNoSpace);

                $answers = $question->accordance_choice_answers;
                foreach ($answers as $key => $answer)
                {
                    $text = $this->clean_text($answer->text);
                    $text2 = $this->clean_text($answer->text2);
                    $n = $key+1;
                    $table->addRow(null,array('tblHeader' => false));
                    $table->addCell(4765)->addText($n.") ".$text,$tableNormalFont,$cellNoSpace);
                    $table->addCell(4765)->addText($symbols[$key].") ".$text2,$tableNormalFont,$cellNoSpace);
                    array_push($rigntAnswersArr,$n."-".$symbols[$key]);
                }
                $t->setComplexBlock('answers#'.$number, $table);
                $t->setValue('rignt_answers#'.$number, $number);
                $t->setValue('rignt_answers_text#'.$number, implode(",",$rigntAnswersArr));

            }

        }

        $t->setValue('testPercent', $dpp->om_version->test_percent);

        $parser = new \HTMLtoOpenXML\Parser();
        $tasks = Task::where('om_version_id',$dpp->om_version_id)->where('task_type_id','<>',2)->orderBy('position')->get();
        $t->cloneBlock('task_block', $tasks->count(), true, true);
        $table_num = 7;
        foreach ($tasks as $key => $task)
        {
            $idx = $key+1;
            $t->setValue('task_position#'.$idx, $task->position);
            $mandatory_text = $task->required == 1 ? "обязательное" : "по выбору";
            $t->setValue('task_mandatory#'.$idx, $mandatory_text);
            $t->setValue('task_place#'.$idx, $task->place);
            $t->setValue('task_time#'.$idx, $task->time);
            $t->setValue('skill_ab#'.$idx, $phrase2);

            $t->setComplexBlock('task_mto#'.$idx, $this->get_task_mto_table($dpp,$task));
            $t->setComplexBlock('task_nsi#'.$idx, $this->get_task_nsi_table($dpp,$task));

            $normalParagraph = array('alignment' => 'left','lineHeight' => 1.5,'spaceAfter' => 0);
            $normalFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 14, 'bold' => false);
            $text = new \PhpOffice\PhpWord\Element\TextRun($normalParagraph);
            $af = $task->additional_files;

            if ($af->count() > 0)
            {
                $text->addTextBreak();
                $text->addText("Дополнительные материалы:");
                $text->addTextBreak();
                foreach ($af as $key=>$value)
                {
                    $num = $key+1;
                    $text->addText($num.". ".$value->name.",");
                    $text->addTextBreak();
                    $text->addText("URL: https://constructor-api.emiit.ru/tasks/".$task->id."/additional_files/".$value->id."/download" );
                    $text->addTextBreak();
                }
            }
            $t->setComplexBlock('dop_mats#'.$idx, $text);

            $subjects = $task->subjects;
            $sub_arr = [];
            foreach ($subjects as $subject)
            {
                if ($subject->type_id != 4)    {$sub = $subject->name;}
                else{$sub = $subject->name.' ('.$subject->type.')';}
                array_push($sub_arr,$sub);
            }
            $t->setValue('task_subject#'.$idx, implode("; ",$sub_arr));
            if ($task->task_type_id == 1) {
                $table_column_1 = "Предмет оценки";
            }else{
                $table_column_1 = "Текст задания";
            }

            $table = new \PhpOffice\PhpWord\Element\Table($tableStyle);
            $table->addRow(null,array('tblHeader' => true));
            $table->addCell(3175)->addText($table_column_1,$tableBoldFont,$cellHCenteredNoSpace);
            $table->addCell(3175)->addText("Объект оценки",$tableBoldFont,$cellHCenteredNoSpace);
            $table->addCell(3175)->addText("Критерий оценки",$tableBoldFont,$cellHCenteredNoSpace);

            $table2 = new \PhpOffice\PhpWord\Element\Table($tableStyle);
            $table2->addRow(null,array('tblHeader' => true));
            $table2->addCell(3175)->addText("Объект оценки",$tableBoldFont,$cellHCenteredNoSpace);
            $table2->addCell(6350)->addText("Модельный ответ (индикатор)",$tableBoldFont,$cellHCenteredNoSpace);
            foreach ($subjects as $subject)
            {
                if ($task->task_type_id == 1) {
                    $objects = $subject->objects;
                    foreach ($objects as $object)
                    {

                        $table->addRow(null,array('tblHeader' => false));
                        $table->addCell(3175)->addText($subject->name,$tableNormalFont,$cellNoSpace);
                        $table->addCell(3175)->addText($object->name,$tableNormalFont,$cellNoSpace);
                        $table->addCell(3175)->addText("Соответствие модельному ответу",$tableNormalFont,$cellNoSpace);

                        $table2->addRow(null,array('tblHeader' => false));
                        $table2->addCell(3175)->addText($this->clean_text($object->name),$tableNormalFont,$cellNoSpace);
                        $text = preg_replace('/[^\pL0-9«»"()=–+^,.;:\-±\s]/u', '', $object->model_answer);
                        $text = mb_convert_encoding($text,'HTML-ENTITIES','UTF-8');
                        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5);
                        $table2->addCell(6350)->addText($text,$tableNormalFont,$cellNoSpace);
                    }
                }elseif ($task->task_type_id == 3) {
                    $steps = $task->steps;
                    foreach ($steps as $key => $step) {
                        $table->addRow(null,array('tblHeader' => false));
                        $htmlToText = new CssToInlineStyles();
                        $step_text = strip_tags($htmlToText->convert($step->text));
                        $table->addCell(3175)->addText("Шаг " . ($key + 1) . "." . $step_text, $tableNormalFont, $cellNoSpace);
                        $table->addCell(3175)->addText($step->object,$tableNormalFont,$cellNoSpace);
                        $table->addCell(3175)->addText("Соответствие модельному ответу",$tableNormalFont,$cellNoSpace);

                        $table2->addRow(null,array('tblHeader' => false));

                        $table2->addCell(3175)->addText($step->object,$tableNormalFont,$cellNoSpace);
                        $htmlToText = new CssToInlineStyles();
                        $right_answer = strip_tags($htmlToText->convert($step->rightAnswer));
                        //$right_answer = str_replace(["\n"], '\r\n', $right_answer);
                        //dd($right_answer);
                        $text = preg_replace('/[^\pL0-9«»"()=–+^,.;:\-±\/\s]/u', '', $right_answer);
                        $text = mb_convert_encoding($text,'HTML-ENTITIES','UTF-8');
                        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5);
                        $text = ltrim($text);
                        $table2->addCell(6350)->addText($text,$tableNormalFont,$cellNoSpace);
                    }
                }
            }
            $t->setComplexBlock('task_criteria#'.$idx, $table);
            $t->setComplexBlock('task_object#'.$idx, $table2);


            $t->setValue('nsi_table_num#'.$idx, $table_num); $table_num++;
            $t->setValue('mto_table_num#'.$idx, $table_num); $table_num++;
            $t->setValue('criteria_table_num#'.$idx, $table_num); $table_num++;
            $t->setValue('model_answer_table_num#'.$idx, $table_num); $table_num++;
        }
        $t->setValue('skill_ability2', $phrase2);

        $proj_tasks = Task::where('om_version_id',$dpp->om_version_id)->where('task_type_id','=',2)->orderBy('position')->get();
        $t->cloneBlock('task_proj_block', $proj_tasks->count(), true, true);
        foreach ($proj_tasks as $key => $proj_task) {
            $idx = $key+1;
            $t->setValue('task_position#'.$idx, $proj_task->position);
            $mandatory_text = $proj_task->required == 1 ? "обязательное" : "по выбору";
            $t->setValue('task_mandatory#'.$idx, $mandatory_text);
            $t->setValue('task_place#'.$idx, $proj_task->place);
            $t->setValue('task_time#'.$idx, $proj_task->time);
            $t->setValue('skill_ab#'.$idx, $phrase2);

            $t->setComplexBlock('task_mto#'.$idx, $this->get_task_mto_table($dpp,$proj_task));
            $t->setComplexBlock('task_nsi#'.$idx, $this->get_task_nsi_table($dpp,$proj_task));

            $normalParagraph = array('alignment' => 'left','lineHeight' => 1.5,'spaceAfter' => 0);
            $normalFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 14, 'bold' => false);
            $text = new \PhpOffice\PhpWord\Element\TextRun($normalParagraph);
            $af = $proj_task->additional_files;

            if ($af->count() > 0)
            {
                $text->addTextBreak();
                $text->addText("Дополнительные материалы:");
                $text->addTextBreak();
                foreach ($af as $key=>$value)
                {
                    $num = $key+1;
                    $text->addText($num.". ".$value->name.",");
                    $text->addTextBreak();
                    $text->addText("URL: https://constructor-api.emiit.ru/tasks/".$proj_task->id."/additional_files/".$value->id."/download" );
                    $text->addTextBreak();
                }
            }
            $t->setComplexBlock('dop_mats#'.$idx, $text);
            $subjects = $proj_task->subjects;
            $sub_arr = [];
            foreach ($subjects as $subject)
            {
                if ($subject->type_id != 4)    {$sub = $subject->name;}
                else{$sub = $subject->name.' ('.$subject->type.')';}
                array_push($sub_arr,$sub);
            }
            $t->setValue('task_subject#'.$idx, implode("; ",$sub_arr));
            $t->setValue('nsi_table_num#'.$idx, $table_num); $table_num++;
            $t->setValue('mto_table_num#'.$idx, $table_num); $table_num++;
        }

        //последний абзац
        //КОЛ-ВО ПРАКТИЧЕСКИХ
        $tasks_count = $ov->tasks()->count();
        $req_tasks = $ov->tasks()->where('required',true)->count() + $ov->optional_tasks;
        $t->setValue('reqPrCount', $req_tasks);
        $t->setValue('prCount', $tasks_count);
        if ($req_tasks == 1) {$prText='практического задания';}
        else {$prText = 'практических заданий';}
        $t->setValue('prText', $prText);



        //временное
        $pathToSave = storage_path('temp/output_'.$dpp->id.'.docx');
        $t->saveAs($pathToSave);

        // // //phpDocx
        $t = new \Phpdocx\Create\CreateDocxFromTemplate(storage_path('temp/output_'.$dpp->id.'.docx'));
        $t->setTemplateSymbol('${', '}');
        // //задания
        $style = "<style>
        table {border: 1px; border-collapse:collapse; width=100%}
        p,li {color: #000000;font-family: Times New Roman;font-size: 14pt;text-align:justify;line-height: 150%;}
        td p{color: #000000;font-family: Times New Roman;font-size: 12pt;text-indent: 1px; margin:0px;line-height: 100%;}
        table tbody tr:first-child td p{font-weight: bold; text-align:center;}
        </style>";
        foreach ($tasks as $key => $task)
        {
            $idx = $key+1;
            $elem = 'task_description#'.$idx;
            $t->replaceVariableByHTML($elem, 'block', $style.$task->description."<p></p>", array('isFile' => false, 'parseDivsAsPs' => true, 'downloadImages' => true,'strictWordStyles' => false));
        }

        foreach ($proj_tasks as $key => $proj_task)
        {
            $idx = $key+1;
            $t->replaceVariableByHTML('p_task_description#'.$idx, 'block', $style.$proj_task->description."<p></p>", array('isFile' => false, 'parseDivsAsPs' => true, 'downloadImages' => true,'strictWordStyles' => false));
            $t->replaceVariableByHTML('p_task_instruction#'.$idx, 'block', $style.$proj_task->portfolioProcedure."<p></p>", array('isFile' => false, 'parseDivsAsPs' => true, 'downloadImages' => true,'strictWordStyles' => false));
            $t->replaceVariableByHTML('p_task_assess#'.$idx, 'block', $style.$proj_task->portfolioCriteria."<p></p>", array('isFile' => false, 'parseDivsAsPs' => true, 'downloadImages' => true,'strictWordStyles' => false));
        }

        // TOC
        $toc = new \Phpdocx\Elements\WordFragment($t);
        $legend = array(
            'text' => 'Щелкните здесь, чтобы обновить содержание',
            'color' => '000000',
            'bold' => false,
            'fontSize' => 14,
        );
        $toc->addTableContents(array('autoUpdate'=>true),$legend,storage_path('/templates/TOC_sample.docx'));
        //$toc->addTableContents();

        $t->replaceVariableByWordFragment(array('TOC' => $toc), array('type' => 'block'));

        $pathToSave = storage_path('ПрДПП_'.$dpp->abbreveation.'_ Приложение А. Оценочные материалы.docx');
        $t->createDocx($pathToSave);
        return response()->download(storage_path('ПрДПП_'.$dpp->abbreveation.'_ Приложение А. Оценочные материалы.docx'));
    }

    public function clean_text($text)
    {
        $text = str_replace("", "", $text);
//        if (str_starts_with($text, "")) {
//            $text = str_replace("", "", $text);
//        }
        $first_char = mb_substr($text, 0, 1);
        if (preg_match('/[^\pL0-9«"()=+-±<>]/u', $first_char) == 1)
        {
            $text = trim($text,$first_char." ");
            $text = trim(preg_replace('/\t+/', '', $text));
        }
        $text = str_replace("\n","\r\n",$text);
        $text = str_replace('(нет текста)','-',$text);
        $text = str_replace('','',$text);
        $text = mb_convert_encoding($text,'HTML-ENTITIES','UTF-8');
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5);


        //$text = htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', true);
        return $text;
    }

    public function parse_text($text)
    {
        $first_char = mb_substr($text, 0, 1);
        if (preg_match('/[\x00-\x08\x0B\x0C\x0E-\x1F\xB7]/', $first_char))
        {
            $text = trim($text,$first_char." ");
            $text = trim(preg_replace('/\t+/', '', $text));
        }else{
            //$text = $text;
        }
        return $text;
    }

    public function get_knowledge_mto_table(Dpp $dpp)
    {
        $tableStyle = array( 'borderColor' => '000000', 'borderSize'  => 1, 'cellMarginLeft' => 107.716535433,'cellMarginRight' => 107.716535433, 'cellMarginTop'  => 0,'cellMarginBottom'  => 0,'width'=> '100%');
        $tableWOBordersStyle = array('cellMargin'  => 50,'width'=> '100%');
        $tableNormalFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 12, 'bold' => false);
        $tableBoldFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 12, 'bold' => true);
        $cellHCenteredNoSpace = array('align' => 'center','lineHeight' => 1,'spaceAfter' => 0,'indentation'=> ['firstLine' => 0]);
        $cellNoSpace = array('spaceAfter' => 0,'lineHeight' => 1,'indentation'=> ['firstLine' => 0]);
        $firstRowStyle = array();
        $table = new \PhpOffice\PhpWord\Element\Table($tableStyle);

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
           $this_mtos = Mto::where('type_id','=',$parent_type->id)->where('dpp_id','=',$dpp->id)->where('is_base_for_lection','=',1)->orderBy('position')->get();
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
                $this_mtos = Mto::where('type_id','=',$children_type->id)->where('dpp_id','=',$dpp->id)->where('is_base_for_lection','=',1)->orderBy('position')->get();
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
        return $table;
    }

    public function get_task_nsi_table (Dpp $dpp, Task $task)
    {
        $tableStyle = array( 'borderColor' => '000000', 'borderSize'  => 1, 'cellMarginLeft' => 107.716535433,'cellMarginRight' => 107.716535433, 'cellMarginTop'  => 0,'cellMarginBottom'  => 0,'width'=> '100%');
        $tableWOBordersStyle = array('cellMargin'  => 50,'width'=> '100%');
        $tableNormalFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 12, 'bold' => false);
        $tableBoldFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 12, 'bold' => true);
        $cellHCenteredNoSpace = array('align' => 'center','lineHeight' => 1,'spaceAfter' => 0,'indentation'=> ['firstLine' => 0]);
        $cellNoSpace = array('spaceAfter' => 0,'lineHeight' => 1,'indentation'=> ['firstLine' => 0]);
        $firstRowStyle = array();
        // НСИ задания
        $table = new \PhpOffice\PhpWord\Element\Table($tableStyle);
        $table->addRow(null,array('tblHeader' => false));
        $table->addCell(9530)->addText("1 Учебно-методическая документация",$tableBoldFont,$cellHCenteredNoSpace);
        $table->addRow(null,array('tblHeader' => false));
        $table->addCell(9530)->addText("1.1 Конспект лекций",$tableNormalFont,$cellNoSpace);
        $table->addRow(null,array('tblHeader' => false));
        $table->addCell(9530)->addText("1.2 Методические указания к организации и проведению практических занятий ",$tableNormalFont,$cellNoSpace);

        foreach ($task->subjects as $key=>$subject)
        {
            if ($key == 0)
            {
                if ($subject->subject_type_id == 2)
                {
                    $zun = $subject->ability;
                    $nsi_base = $zun->nsis;
                }else if ($subject->subject_type_id > 2)
                {
                    $zun = $subject->skill;
                    $nsi_base = $zun->nsis;
                }
            }else{
                if ($subject->subject_type_id == 2)
                {
                    $zun = $subject->ability;
                    $nsi_base = $nsi_base->merge($zun->nsis);
                }else if ($subject->subject_type_id > 2)
                {
                    $zun = $subject->skill;
                    $nsi_base = $nsi_base->merge($zun->nsis);
                }
            }
        }
        $ids = $nsi_base->pluck(['id']);

        if (count($ids) > 0)
        {
            $table->addRow(null,array('tblHeader' => false));
            $table->addCell(9530)->addText("2 Список используемых источников",$tableBoldFont,$cellHCenteredNoSpace);
            $part2_pos = 0;
            /* НСИ 2-1 */
            $nsis = Nsi::whereIn('nsis.id', $ids)
            ->join('nsi_types', 'nsis.type_id', '=', 'nsi_types.id')
            ->where('nsi_types.part',1)
            ->orderBy('nsi_types.position')
            ->select('nsis.nsiFullName')
            ->get();
            if ($nsis->count() > 0)
            {
                $part2_pos++;
                $table->addRow(null,array('tblHeader' => false));
                $table->addCell(9530)->addText("2.".$part2_pos." Нормативные правовые акты, нормативная техническая документация, иная документация",$tableNormalFont,$cellNoSpace);
            }
            foreach($nsis as $key=>$nsi)
            {
                $nsi_pos = $key+1;
                $table->addRow(null,array('tblHeader' => false));
                $table->addCell(9530)->addText("2.".$part2_pos.".".$nsi_pos." ".$this->clean_text($nsi->nsiFullName),$tableNormalFont,$cellNoSpace);
            }
            /* НСИ 2-2 */
            $nsis = Nsi::whereIn('nsis.id', $ids)
            ->join('nsi_types', 'nsis.type_id', '=', 'nsi_types.id')
            ->where('nsi_types.part',2)
            ->orderBy('nsi_types.position')
            ->select('nsis.nsiFullName')
            ->get();
            if ($nsis->count() > 0)
            {
                $part2_pos++;
                $table->addRow(null,array('tblHeader' => false));
                $table->addCell(9530)->addText("2.".$part2_pos." Учебники, монографии",$tableNormalFont,$cellNoSpace);
            }
            foreach($nsis as $key=>$nsi)
            {
                $nsi_pos = $key+1;
                $table->addRow(null,array('tblHeader' => false));
                $table->addCell(9530)->addText("2.".$part2_pos.".".$nsi_pos." ".$this->clean_text($nsi->nsiFullName),$tableNormalFont,$cellNoSpace);
            }
            /* НСИ 3 */
            $nsis = Nsi::whereIn('nsis.id', $ids)
            ->join('nsi_types', 'nsis.type_id', '=', 'nsi_types.id')
            ->where('nsi_types.part',3)
            ->orderBy('nsi_types.position')
            ->select('nsis.nsiFullName')
            ->get();
            if ($nsis->count() > 0)
            {
                $part2_pos++;
                $table->addRow(null,array('tblHeader' => false));
                $table->addCell(9530)->addText("2.".$part2_pos." Интернет ресурсы",$tableBoldFont,$cellHCenteredNoSpace);
            }
            foreach($nsis as $key=>$nsi)
            {
                $nsi_pos = $key+1;
                $table->addRow(null,array('tblHeader' => false));
                $table->addCell(9530)->addText("2.".$part2_pos.".".$nsi_pos." ".$this->clean_text($nsi->nsiFullName),$tableNormalFont,$cellNoSpace);
            }
            $table->addRow(null,array('tblHeader' => false));
            $table->addCell(9530)->addText("3 Информационное обеспечение",$tableBoldFont,$cellHCenteredNoSpace);
            $table->addRow(null,array('tblHeader' => false));
            $table->addCell(9530)->addText("3.1 http://library.miit.ru/",$tableNormalFont,$cellNoSpace);
        }
        return $table;

    }

    public function get_task_mto_table (Dpp $dpp,Task $task)
    {
        $tableStyle = array( 'borderColor' => '000000', 'borderSize'  => 1, 'cellMarginLeft' => 107.716535433,'cellMarginRight' => 107.716535433, 'cellMarginTop'  => 0,'cellMarginBottom'  => 0,'width'=> '100%');
        $tableWOBordersStyle = array('cellMargin'  => 50,'width'=> '100%');
        $tableNormalFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 12, 'bold' => false);
        $tableBoldFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 12, 'bold' => true);
        $cellHCenteredNoSpace = array('align' => 'center','lineHeight' => 1,'spaceAfter' => 0,'indentation'=> ['firstLine' => 0]);
        $cellNoSpace = array('spaceAfter' => 0,'lineHeight' => 1,'indentation'=> ['firstLine' => 0]);
        $firstRowStyle = array();

        $table = new \PhpOffice\PhpWord\Element\Table($tableStyle);
        $parent_types= MtoType::where('parent_id','=',null)->get();
        $f_n = 0;
        foreach ($parent_types as $parent_type)
        {
            $children_types =  MtoType::where('parent_id','=',$parent_type->id)->get();
            $this_mtos = $task->mtos->where('type_id','=',$parent_type->id)->sortBy('position');
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
                    $this_mtos = $task->mtos->where('type_id','=',$children_type->id)->sortBy('position');
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
        return $table;
    }


    public function get_nsi (Dpp $dpp)
    {
        $tableStyle = array( 'borderColor' => '000000', 'borderSize'  => 1, 'cellMarginLeft' => 107.716535433,'cellMarginRight' => 107.716535433, 'cellMarginTop'  => 0,'cellMarginBottom'  => 0,'width'=> '100%');
        $tableWOBordersStyle = array('cellMargin'  => 50,'width'=> '100%');
        $tableNormalFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 12, 'bold' => false);
        $tableBoldFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 12, 'bold' => true);
        $cellHCenteredNoSpace = array('align' => 'center','lineHeight' => 1,'spaceAfter' => 0,'indentation'=> ['firstLine' => 0]);
        $cellNoSpace = array('spaceAfter' => 0,'lineHeight' => 1,'indentation'=> ['firstLine' => 0]);
        $firstRowStyle = array();
        $table = new \PhpOffice\PhpWord\Element\Table($tableStyle);
        $table->addRow(null,array('tblHeader' => false));
        $table->addCell(9530)->addText("1 Учебно-методическая документация",$tableBoldFont,$cellHCenteredNoSpace);
        $table->addRow(null,array('tblHeader' => false));
        $table->addCell(9530)->addText("1.1 Конспект лекций",$tableNormalFont,$cellNoSpace);
        $table->addRow(null,array('tblHeader' => false));
        $table->addCell(9530)->addText("1.2 Методические указания к организации и проведению практических занятий ",$tableNormalFont,$cellNoSpace);
        $table->addRow(null,array('tblHeader' => false));
        $table->addCell(9530)->addText("2 Литература",$tableBoldFont,$cellHCenteredNoSpace);
        $part2_pos = 0;
        /* НСИ 2-1 */
        $nsis = Nsi::where('ish_version_id',$dpp->ish_version_id)
        ->join('nsi_types', 'nsis.type_id', '=', 'nsi_types.id')
        ->where('nsi_types.part',1)
        ->orderBy('nsi_types.position')
        ->select('nsis.nsiFullName')
        ->get();
        if ($nsis->count() > 0)
        {
            $part2_pos++;
            $table->addRow(null,array('tblHeader' => false));
            $table->addCell(9530)->addText("2.".$part2_pos." Нормативные правовые акты, нормативная техническая документация, иная документация",$tableNormalFont,$cellNoSpace);
        }
        foreach($nsis as $key=>$nsi)
        {
            $nsi_pos = $key+1;
            $table->addRow(null,array('tblHeader' => false));
            $table->addCell(9530)->addText("2.".$part2_pos.".".$nsi_pos." ".$nsi->nsiFullName,$tableNormalFont,$cellNoSpace);
        }
        /* НСИ 2-2 */
        $nsis = Nsi::where('ish_version_id',$dpp->ish_version_id)
        ->join('nsi_types', 'nsis.type_id', '=', 'nsi_types.id')
        ->where('nsi_types.part',2)
        ->orderBy('nsi_types.position')
        ->select('nsis.nsiFullName')
        ->get();
        if ($nsis->count() > 0)
        {
            $part2_pos++;
            $table->addRow(null,array('tblHeader' => false));
            $table->addCell(9530)->addText("2.".$part2_pos." Учебники, монографии",$tableNormalFont,$cellNoSpace);
        }
        foreach($nsis as $key=>$nsi)
        {
            $nsi_pos = $key+1;
            $table->addRow(null,array('tblHeader' => false));
            $table->addCell(9530)->addText("2.".$part2_pos.".".$nsi_pos." ".$nsi->nsiFullName,$tableNormalFont,$cellNoSpace);
        }
        /* НСИ 3 */
        $nsis = Nsi::where('ish_version_id',$dpp->ish_version_id)
        ->join('nsi_types', 'nsis.type_id', '=', 'nsi_types.id')
        ->where('nsi_types.part',3)
        ->orderBy('nsi_types.position')
        ->select('nsis.nsiFullName')
        ->get();
        if ($nsis->count() > 0)
        {
            $part2_pos++;
            $table->addRow(null,array('tblHeader' => false));
            $table->addCell(9530)->addText("3. Интернет ресурсы",$tableBoldFont,$cellHCenteredNoSpace);
        }
        foreach($nsis as $key=>$nsi)
        {
            $nsi_pos = $key+1;
            $table->addRow(null,array('tblHeader' => false));
            $table->addCell(9530)->addText("3.".$nsi_pos." ".$nsi->nsiFullName,$tableNormalFont,$cellNoSpace);
        }
        $table->addRow(null,array('tblHeader' => false));
        $table->addCell(9530)->addText("4 Электронно-библиотечная система",$tableBoldFont,$cellHCenteredNoSpace);
        $table->addRow(null,array('tblHeader' => false));
        $table->addCell(9530)->addText("4.1 Определяются образовательной организацией ",$tableNormalFont,$cellNoSpace);
        return $table;
    }

    public function export_om2 (Dpp $dpp)
    {
        $t = new \Phpdocx\Create\CreateDocxFromTemplate(storage_path('/templates/make_om2.docx'));
        $t->setTemplateSymbol('${', '}');
        $variables = $t->getTemplateVariables();
        $t->processTemplate($variables);

       // $t->parseStyles();
       // $t->createDocx(public_path() . '/output.docx');
       // return response()->download(public_path() . '/output.docx');
        //ТИТУЛ
        $t->replaceVariableByText(array('dppName' => $dpp->name),array('raw' => true));
        $t->replaceVariableByText(array('year' => $dpp->year),array('raw' => true));
        //ТАБЛИЦА 2
        $zv = $dpp->zun_version_id;
        $competences = Competence::where('zun_version_id','=',$zv)->get();
        $data = [];
        foreach ($competences as $index => $competence)
        {
            $i = $index +1;

            $knowledges = Knowledge::where('zun_version_id','=',$zv)->get()->pluck("name")->toArray();
            $knowledges = implode("; ",$knowledges);

            $abilities = Ability::where('zun_version_id','=',$zv)->get()->pluck("name")->toArray();
            $abilities = implode("; ",$abilities);

            $skills = Skill::where('zun_version_id','=',$zv)->get()->pluck("name")->toArray();
            $skills = implode("; ",$skills);

            array_push($data,array(
                'competence' => $competence->name,
                'knowledges' => $knowledges,
                'abilities' => $abilities,
                'skills' => $skills
            ));
        }
        $t->replaceTableVariable($data);

        // ТАБЛИЦА 3
        $knowledges = Knowledge::where('zun_version_id','=',$zv)->get();
        $number = 0;
        $table_3_data = [];
        foreach ($knowledges as  $index => $knowledge)
        {
            $i = $index + 1;
            $questions = $knowledge->questions;
            $type2 = $questions->where('question_type_id',2);
            $type = $questions->where('question_type_id',1)->union($type2);
            $qtn = new \Phpdocx\Elements\WordFragment($t);
            if ($type->count() > 0)
            {
                $arr = [];
                foreach ($type as $value) {$number++; array_push($arr,$number);}
                $qtn->addText('Задания с выбором ответа: '.implode(', ',$arr),['pStyle' =>'afe']);
            }
            $type = $questions->where('question_type_id',3);
            if ($type->count() > 0)
            {
                $arr = [];
                foreach ($type as $value) {$number++; array_push($arr,$number);}
                $qtn->addText('Задания с открытым ответом: '.implode(', ',$arr),['linebreak'=>'before','pStyle' =>'afe']);
            }
            $type = $questions->where('question_type_id',4);
            if ($type->count() > 0)
            {
                $arr = [];
                foreach ($type as $value) {$number++; array_push($arr,$number);}
                $qtn->addText('Задания на установление последовательности: '.implode(', ',$arr),['linebreak'=>'before','pStyle' =>'afe']);
            }
            $type = $questions->where('question_type_id',5);
            if ($type->count() > 0)
            {
                $arr = [];
                foreach ($type as $value) {$number++; array_push($arr,$number);}
                $qtn->addText('Задания на установление соответствия: '.implode(', ',$arr),['linebreak'=>'before','pStyle' =>'afe']);
            }

            $table_3_row = [
                'knowledge' => $knowledge->name,
                'qtn' =>  $qtn
            ];
            array_push($table_3_data,$table_3_row);
        }
        $t->replaceTableVariable($table_3_data);

        $ov = OmVersion::find($dpp->om_version_id);
        //$questions = $ov->questions;
        $questions = Question::where('om_version_id',$dpp->om_version_id)
        ->join('knowledge', 'questions.knowledge_id', '=', 'knowledge.id')
        ->where('knowledge.deleted_at',null)
        ->get();
        $type2 = $questions->where('question_type_id',2);
        $type = $questions->where('question_type_id',1)->union($type2);
        $t->replaceVariableByText(array('QChooseCount' =>  $type->count()),array('raw' => true));
        $type = $questions->where('question_type_id',3);
        $t->replaceVariableByText(array('QFreeCount' =>  $type->count()),array('raw' => true));
        $type = $questions->where('question_type_id',4);
        $t->replaceVariableByText(array('QSequenceCount' =>  $type->count()),array('raw' => true));
        $type = $questions->where('question_type_id',5);
        $t->replaceVariableByText(array('QAccordanceCount' =>  $type->count()),array('raw' => true));

        $rows = 0;
        $table_4_data = [];

        $skills = Skill::where('zun_version_id','=',$zv)->get();
        $abilities = Ability::where('zun_version_id','=',$zv)->get();

        $current_row = 0;
        $task_time = 0;
        foreach ($skills as $skill)
        {
            $id = $skill->id;
            $skill_tasks = Task::whereHas('subjects', function ($query) use ($id) {
                return $query->where('skill_id', '=', $id);
            })->get();
            foreach ($skill_tasks as $task)
            {
                $current_row++;
                $positions = $skill_tasks->pluck('position')->toArray();
                $positions = implode(", ",$positions);
                $table_4_row = [
                    'sa' => $skill->name,
                    'saTaskType' => "задание на применение умений и навыков в модельных условиях",
                    'saTaskNumber' => $positions
                ];
                array_push($table_4_data,$table_4_row);
                $task_time += $task->time / 45;
            }
        }

        foreach ($abilities as $ability)
        {
            $id = $ability->id;
            $ability_tasks = Task::whereHas('subjects', function ($query) use ($id) {
                return $query->where('ability_id', '=', $id);
            })->get();
            foreach ($ability_tasks as $task)
            {
                $current_row++;
                $positions = $ability_tasks->pluck('position')->toArray();
                $positions = implode(", ",$positions);
                $table_4_row = [
                    'sa' => $ability->name,
                    'saTaskType' => "задание на применение умений в модельных условиях",
                    'saTaskNumber' => $positions
                ];
                array_push($table_4_data,$table_4_row);
                $task_time += $task->time / 45;
            }
        }
        $t->replaceTableVariable($table_4_data);

        $t->replaceVariableByText(array('taskHours' =>  $task_time),array('raw' => true));

        //5.2 Тестовые задания
        $knowledges = Knowledge::where('zun_version_id','=',$zv)->get();
        $questions = Question::where('om_version_id',$dpp->om_version_id)->join('knowledge', 'questions.knowledge_id', '=', 'knowledge.id')
        ->where('knowledge.deleted_at',null)
        ->get();




        //$t->cloneRow('rignt_answers', $questions->count());
        $number = 0;
        $symbols = ['a','б','в','г','д','е','ж','з','и','к','л','м','н','о','п','р'];
        $normalFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 14, 'bold' => false);
        $normalParagraph = array('alignment' => 'both','lineHeight' => 1.5,'spaceAfter' => 0);
        $normalParagraphLH1 = array('alignment' => 'both','lineHeight' => 1,'spaceAfter' => 0);
        for ($i = 0; $i<$questions->count(); $i++)
        {
                $t->cloneBlock('QUESTION');
        }


        $t->createDocx(public_path() . '/output.docx');
        return response()->download(public_path() . '/output.docx');
    }


    public function export_om_rnd(Dpp $dpp)
    {
        $t = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('/templates/make_om.docx'));
        \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true);
        //ТИТУЛ
        $t->setValue('dppName', $dpp->name);
        $t->setValue('year', $dpp->year);

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

            $abilities = Ability::where('zun_version_id','=',$zv)->where('competence_id','=',$competence->id)->get()->pluck("name")->toArray();
            $abilities = implode("; ",$abilities);
            $t->setValue('abilities#'.$i, $abilities);

            $skills = Skill::where('zun_version_id','=',$zv)->where('competence_id','=',$competence->id)->get()->pluck("name")->toArray();
            $skills = implode("; ",$skills);
            $t->setValue('skills#'.$i, $skills);
        }

        // ТАБЛИЦА 3

        $knowledges = Knowledge::where('zun_version_id','=',$zv)->get();
        $t->cloneRow('knowledge', $knowledges->count());
        $number = 0;

        foreach ($knowledges as  $index => $knowledge)
        {
            $i = $index + 1;
            $t->setValue('knowledge#'.$i, $knowledge->name);
            //qtn
            $qtn = new \PhpOffice\PhpWord\Element\TextRun();
            $questions = $knowledge->questions;
            $type2 = $questions->where('question_type_id',2);
            $type = $questions->where('question_type_id',1)->union($type2);
            if ($type->count() > 0)
            {
                $qtn->addText('Задания с выбором ответа: ');
                $arr = [];
                foreach ($type as $value) {$number++; array_push($arr,$number);}
                $qtn->addText(implode(', ',$arr));
            }
            $type = $questions->where('question_type_id',3);
            if ($type->count() > 0)
            {
                $qtn->addTextBreak();
                $qtn->addText('Задания с открытым ответом: ');
                $arr = [];
                foreach ($type as $value) {$number++; array_push($arr,$number);}
                $qtn->addText(implode(', ',$arr));
            }
            $type = $questions->where('question_type_id',4);
            if ($type->count() > 0)
            {
                $qtn->addTextBreak();
                $qtn->addText('Задания на установление последовательности: ');
                $arr = [];
                foreach ($type as $value) {$number++; array_push($arr,$number);}
                $qtn->addText(implode(', ',$arr));
            }
            $type = $questions->where('question_type_id',5);
            if ($type->count() > 0)
            {
                $qtn->addTextBreak();
                $qtn->addText('Задания на установление соответствия: ');
                $arr = [];
                foreach ($type as $value) {$number++; array_push($arr,$number);}
                $qtn->addText(implode(', ',$arr));
            }

            $t->setComplexValue('qtn#'.$i, $qtn);
        }

        $ov = OmVersion::find($dpp->om_version_id);
        //$questions = $ov->questions;
        $questions = Question::where('om_version_id',$dpp->om_version_id)
        ->join('knowledge', 'questions.knowledge_id', '=', 'knowledge.id')
        ->where('knowledge.deleted_at',null)
        ->get();
        $type2 = $questions->where('question_type_id',2);
        $type = $questions->where('question_type_id',1)->union($type2);
        $t->setValue('QChooseCount', $type->count());
        $type = $questions->where('question_type_id',3);
        $t->setValue('QFreeCount', $type->count());
        $type = $questions->where('question_type_id',4);
        $t->setValue('QSequenceCount', $type->count());
        $type = $questions->where('question_type_id',5);
        $t->setValue('QAccordanceCount', $type->count());

        //ТАБЛИЦА 4
        $rows = 0;
        $task_time = 0;
        $skills = Skill::where('zun_version_id','=',$zv)->get();
        foreach ($skills as $skill)
        {
            $id = $skill->id;
            $skill_tasks = Task::whereHas('subjects', function ($query) use ($id) {
                return $query->where('skill_id', '=', $id);
            })->get();
            $rows += $skill_tasks->count();
        }

        $abilities = Ability::where('zun_version_id','=',$zv)->get();
        foreach ($abilities as $ability)
        {
            $id = $ability->id;
            $ability_tasks = Task::whereHas('subjects', function ($query) use ($id) {
                return $query->where('ability_id', '=', $id);
            })->get();
            $rows += $ability_tasks->count();
        }
        $t->cloneRow('sa', $rows);

        $current_row = 0;
        foreach ($skills as $skill)
        {
            $id = $skill->id;
            $skill_tasks = Task::whereHas('subjects', function ($query) use ($id) {
                return $query->where('skill_id', '=', $id);
            })->get();
            foreach ($skill_tasks as $task)
            {
                $current_row++;
                $positions = $skill_tasks->pluck('position')->toArray();
                $positions = implode(", ",$positions);
                $t->setValue('sa#'.$current_row, $ability->name);
                $t->setValue('saTaskType#'.$current_row,"задание на применение умений и навыков в модельных условиях");
                $t->setValue('saTaskNumber#'.$current_row,$positions);
                $task_time += $task->time;
            }
        }

        foreach ($abilities as $ability)
        {
            $id = $ability->id;
            $ability_tasks = Task::whereHas('subjects', function ($query) use ($id) {
                return $query->where('ability_id', '=', $id);
            })->get();
            foreach ($ability_tasks as $task)
            {
                $current_row++;
                $positions = $ability_tasks->pluck('position')->toArray();
                $positions = implode(", ",$positions);
                $t->setValue('sa#'.$current_row, $ability->name);
                $t->setValue('saTaskType#'.$current_row,"задание на применение умений в модельных условиях");
                $t->setValue('saTaskNumber#'.$current_row,$positions);
                $task_time += $task->time;
            }
        }
        dd(round($task_time / 45,2));
        $t->setValue('taskHours',round($task_time / 45,2));

        try {
            $t->setComplexBlock('knowledge_mto',  $this->get_knowledge_mto_table($dpp));
        } catch (Exception $e) {
            dd('Ошибка в МТО знаний');
        }

        try {
            $t->setComplexBlock('nsi', $this->get_nsi($dpp));
        } catch (Exception $e) {
            dd('Ошибка в НСИ');
        }

        $knowledges = Knowledge::where('zun_version_id','=',$zv)->get();
        $questions = Question::where('om_version_id',$dpp->om_version_id)->join('knowledge', 'questions.knowledge_id', '=', 'knowledge.id')
        ->where('knowledge.deleted_at',null)
        ->get();
        $t->cloneBlock('question_block', $questions->count(), true, true);
        $t->cloneRow('rignt_answers', $questions->count());
        $number = 0;
        $symbols = ['a','б','в','г','д','е','ж','з','и','к','л','м','н','о','п','р'];
        $normalFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 14, 'bold' => false);
        $normalParagraph = array('alignment' => 'both','lineHeight' => 1.5,'spaceAfter' => 0);
        $normalParagraphLH1 = array('alignment' => 'both','lineHeight' => 1,'spaceAfter' => 0);
        $tableStyle = array( 'borderColor' => '000000', 'borderSize'  => 1, 'cellMarginLeft' => 107.716535433,'cellMarginRight' => 107.716535433, 'cellMarginTop'  => 0,'cellMarginBottom'  => 0,'width'=> '100%');
        $tableWOBordersStyle = array('cellMargin'  => 50,'width'=> '100%');
        $tableNormalFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 12, 'bold' => false);
        $tableBoldFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 12, 'bold' => true);
        $cellHCenteredNoSpace = array('align' => 'center','lineHeight' => 1,'spaceAfter' => 0,'indentation'=> ['firstLine' => 0]);
        $cellNoSpace = array('spaceAfter' => 0,'lineHeight' => 1,'indentation'=> ['firstLine' => 0]);
        $firstRowStyle = array();
        foreach ($knowledges as  $index => $knowledge)
        {
            $questions = $knowledge->questions;
            $type2 = $questions->where('question_type_id',2);
            $type = $questions->where('question_type_id',1)->union($type2);
            foreach ($type as $question)
            {
                $number++;
                $rigntAnswersArr = [];
                $t->setValue('text#'.$number, $number." ".$question->text);
                $t->setValue('type#'.$number, $question->type->name);
                if ($question->question_type_id == 1)
                {
                    $table  = new \PhpOffice\PhpWord\Element\Table();
                    $answers = $question->single_choice_answers;
                    foreach ($answers as $key => $answer)
                    {
                        $text = str_replace('&','&#38;',$answer->text);
                        $text = str_replace('<','&#60;',$text);
                        $text = str_replace('>','&#62;',$text);
                        $text = str_replace('(нет текста)','-',$text);
                        if ($key != $answers->count()-1) {$end=';';}else{$end='.';}
                        $table->addRow(null);
                        $table->addCell(9530)->addText($symbols[$key].") ".$text.$end,$normalFont,$normalParagraphLH1);
                        if ($answer->is_right == 1) {array_push($rigntAnswersArr,$symbols[$key]);}
                    }
                     $t->setComplexBlock('answers#'.$number, $table);
                     $t->setValue('rignt_answers#'.$number, $number);
                     $t->setValue('rignt_answers_text#'.$number, implode(",",$rigntAnswersArr));
                }
                if ($question->question_type_id == 2)
                {
                    $table  = new \PhpOffice\PhpWord\Element\Table();
                    $answers = $question->multi_choice_answers;
                    foreach ($answers as $key => $answer)
                    {
                        $text = str_replace('&','&#38;',$answer->text);
                        $text = str_replace('<','&#60;',$text);
                        $text = str_replace('>','&#62;',$text);
                        $text = str_replace('(нет текста)','-',$text);
                        if ($key != $answers->count()-1) {$end=';';}else{$end='.';}
                        $table->addRow(null);
                        $table->addCell(9530)->addText($symbols[$key].") ".$text.$end,$normalFont,$normalParagraphLH1);
                        if ($answer->is_right == 1) {array_push($rigntAnswersArr,$symbols[$key]);}
                    }
                     $t->setComplexBlock('answers#'.$number, $table);
                     $t->setValue('rignt_answers#'.$number, $number);
                     $t->setValue('rignt_answers_text#'.$number, implode(",",$rigntAnswersArr));
                }
            }

            $type = $questions->where('question_type_id',3);
            foreach ($type as $question)
            {
                $number++;
                $rigntAnswersArr = [];
                $answers = $question->free_choice_answers;
                $t->setValue('text#'.$number, $number." ".$question->text);
                $t->setValue('type#'.$number, $question->type->name);
                $t->setValue('answers#'.$number, " ");
                $text = str_replace('(нет текста)','-',$text);
                foreach ($answers as $key => $answer)
                {
                    array_push($rigntAnswersArr,$answer->text);
                }
                $t->setValue('rignt_answers#'.$number, $number);
                $t->setValue('rignt_answers_text#'.$number, implode("; ",$rigntAnswersArr));
            }

            $type = $questions->where('question_type_id',4);
            foreach ($type as $question)
            {
                $number++;
                $rigntAnswersArr = [];
                $t->setValue('text#'.$number, $number." ".$question->text);
                $t->setValue('type#'.$number, $question->type->name);
                $text = str_replace('(нет текста)','-',$text);
                $table  = new \PhpOffice\PhpWord\Element\Table();
                $answers = $question->sequence_choice_answers;
                foreach ($answers as $key => $answer)
                {
                    $n = $key+1;
                    $table->addRow(null);
                    $table->addCell(9530)->addText($n." ".$answer->text,$normalFont,$normalParagraphLH1);
                    array_push($rigntAnswersArr,$n);
                }

                $t->setComplexBlock('answers#'.$number, $table);
                $t->setValue('rignt_answers#'.$number, $number);
                $t->setValue('rignt_answers_text#'.$number, implode(",",$rigntAnswersArr));
            }

            $type = $questions->where('question_type_id',5);
            foreach ($type as $question)
            {
                $number++;
                $rigntAnswersArr = [];
                $t->setValue('text#'.$number, $number." ".$question->text);
                $t->setValue('type#'.$number, $question->type->name);
                $table  = new \PhpOffice\PhpWord\Element\Table($tableStyle);
                $table->addRow(null,array('tblHeader' => true));
                $table->addCell(4765)->addText("Колонка 1",$tableBoldFont,$cellHCenteredNoSpace);
                $table->addCell(4765)->addText("Колонка 2",$tableBoldFont,$cellHCenteredNoSpace);

                $answers = $question->accordance_choice_answers;
                foreach ($answers as $key => $answer)
                {
                    $n = $key+1;
                    $table->addRow(null,array('tblHeader' => false));
                    $table->addCell(4765)->addText($n.") ".$answer->text,$tableNormalFont,$cellNoSpace);
                    $table->addCell(4765)->addText($symbols[$key].") ".$answer->text2,$tableNormalFont,$cellNoSpace);
                    array_push($rigntAnswersArr,$n."-".$symbols[$key]);
                }
                $t->setComplexBlock('answers#'.$number, $table);
                $t->setValue('rignt_answers#'.$number, $number);
                $t->setValue('rignt_answers_text#'.$number, implode(",",$rigntAnswersArr));

            }

        }

        $parser = new \HTMLtoOpenXML\Parser();
        $tasks = Task::where('om_version_id',$dpp->om_version_id)->where('task_type_id',1)->orderBy('position')->get();
        $t->cloneBlock('task_block', $tasks->count(), true, true);
        $table_num = 7;
        foreach ($tasks as $key => $task)
        {
            $idx = $key+1;
            $t->setValue('task_position#'.$idx, $task->position);
            $mandatory_text = $task->required == 1 ? "обязательное" : "по выбору";
            $t->setValue('task_mandatory#'.$idx, $mandatory_text);
            $t->setValue('task_place#'.$idx, $task->place);
            $t->setValue('task_time#'.$idx, $task->time);


            $t->setComplexBlock('task_mto#'.$idx, $this->get_task_mto_table($dpp,$task));
            $t->setComplexBlock('task_nsi#'.$idx, $this->get_task_nsi_table($dpp,$task));
            $subjects = $task->subjects;
            $sub_arr = [];
            foreach ($subjects as $subject)
            {
                if ($subject->type_id != 4)    {$sub = $subject->name;}
                else{$sub = $subject->name.' ('.$subject->type.')';}
                array_push($sub_arr,$sub);
            }
            $t->setValue('task_subject#'.$idx, implode("; ",$sub_arr));

            $table = new \PhpOffice\PhpWord\Element\Table($tableStyle);
            $table->addRow(null,array('tblHeader' => true));
            $table->addCell(3175)->addText("Предмет оценки",$tableBoldFont,$cellHCenteredNoSpace);
            $table->addCell(3175)->addText("Объект оценки",$tableBoldFont,$cellHCenteredNoSpace);
            $table->addCell(3175)->addText("Критерий оценки",$tableBoldFont,$cellHCenteredNoSpace);

            $table2 = new \PhpOffice\PhpWord\Element\Table($tableStyle);
            $table2->addRow(null,array('tblHeader' => true));
            $table2->addCell(3175)->addText("Объект оценки",$tableBoldFont,$cellHCenteredNoSpace);
            $table2->addCell(6350)->addText("Модельный ответ (индикатор)",$tableBoldFont,$cellHCenteredNoSpace);
            foreach ($subjects as $subject)
            {
                $objects = $subject->objects;
                foreach ($objects as $object)
                {
                    $table->addRow(null,array('tblHeader' => false));
                    $table->addCell(3175)->addText($subject->name,$tableNormalFont,$cellNoSpace);
                    $table->addCell(3175)->addText($object->name,$tableNormalFont,$cellNoSpace);
                    $table->addCell(3175)->addText("Соответствие модельному ответу",$tableNormalFont,$cellNoSpace);

                    $table2->addRow(null,array('tblHeader' => false));
                    $table2->addCell(3175)->addText($object->name,$tableNormalFont,$cellNoSpace);
                    $table2->addCell(6350)->addText($object->model_answer,$tableNormalFont,$cellNoSpace);
                }
            }
            $t->setComplexBlock('task_criteria#'.$idx, $table);
            $t->setComplexBlock('task_object#'.$idx, $table2);


            $t->setValue('nsi_table_num#'.$idx, $table_num); $table_num++;
            $t->setValue('mto_table_num#'.$idx, $table_num); $table_num++;
            $t->setValue('criteria_table_num#'.$idx, $table_num); $table_num++;
            $t->setValue('model_answer_table_num#'.$idx, $table_num); $table_num++;
        }






        //временное
        $pathToSave = storage_path('temp/output_'.$dpp->id.'.docx');
        $t->saveAs($pathToSave);

        // // //phpDocx
        $t = new \Phpdocx\Create\CreateDocxFromTemplate(storage_path('temp/output_'.$dpp->id.'.docx'));
        $t->setTemplateSymbol('${', '}');
        // //задания
        $style = "<style>
        table {border: 1px; border-collapse:collapse; width=100%}
        p,li {color: #000000;font-family: Times New Roman;font-size: 14pt;text-align:justify;line-height: 150%;}
        td p{color: #000000;font-family: Times New Roman;font-size: 12pt;text-indent: 1px; margin:0px;line-height: 100%;}
        table tbody tr:first-child td p{font-weight: bold; text-align:center;}
        </style>
        ";
        foreach ($tasks as $key => $task)
        {
            $test = $style . $task->description;
            $idx = $key+1;
            $elem = 'task_description#'.$idx;
            $t->replaceVariableByHTML($elem, 'block', $style.$task->description."<p></p>", array('isFile' => false, 'parseDivsAsPs' => true, 'downloadImages' => true,'strictWordStyles' => false));
        }
        // TOC
        $toc = new \Phpdocx\Elements\WordFragment($t);
        $legend = array(
            'text' => 'Щелкните здесь, чтобы обновить содержание',
            'color' => '000000',
            'bold' => false,
            'fontSize' => 14,
        );
        $toc->addTableContents(array('autoUpdate'=>true),$legend,storage_path('/templates/TOC_sample.docx'));
        //$t->replaceVariableByWordFragment(array('TOC' => $toc), array('type' => 'block'));

        $pathToSave = storage_path('ПрДПП_'.$dpp->abbreveation.'_ Приложение А. Оценочные материалы.docx');
        $t->createDocx($pathToSave);
        return response()->download(storage_path('ПрДПП_'.$dpp->abbreveation.'_ Приложение А. Оценочные материалы.docx'));
    }

    public function get_nsi_table(Dpp $dpp)
    {
        $tableStyle = array( 'borderColor' => '000000', 'borderSize'  => 1, 'cellMarginLeft' => 107.716535433,'cellMarginRight' => 107.716535433, 'cellMarginTop'  => 0,'cellMarginBottom'  => 0,'width'=> '100%');
        $tableBoldFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 12, 'bold' => true);
        $cellHCenteredNoSpace = array('align' => 'center','spaceAfter' => 0, 'indentation'=> ['firstLine' => 0]);
        $tableNormalFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 12, 'bold' => false);
        $cellNoSpace = array('spaceAfter' => 0,'lineHeight' => 1, 'indentation'=> ['firstLine' => 0]);
        $cellNoSpaceJustify = array('spaceAfter' => 0, 'lineHeight' => 1, 'align' => 'both', 'indentation'=> ['firstLine' => 0]);
        $table = new \PhpOffice\PhpWord\Element\Table($tableStyle);
        $table->addRow(null,array('tblHeader' => false));
        $table->addCell(9530)->addText("Вид информационного и учебно-методического обеспечения",$tableBoldFont,$cellHCenteredNoSpace);
        $table->addRow(null,array('tblHeader' => false));
        $table->addCell(9530)->addText("1 Учебно-методическая документация",$tableBoldFont,$cellHCenteredNoSpace);
        $table->addRow(null,array('tblHeader' => false));
        $table->addCell(9530)->addText("1.1 Конспект лекций",$tableNormalFont,$cellNoSpaceJustify);
        $table->addRow(null,array('tblHeader' => false));
        $table->addCell(9530)->addText("1.2 Методические указания к организации и проведению практических занятий ",$tableNormalFont,$cellNoSpaceJustify);
        $table->addRow(null,array('tblHeader' => false));
        $table->addCell(9530)->addText("2 Список используемых источников",$tableBoldFont,$cellHCenteredNoSpace);
        $part2_pos = 0;
        /* НСИ 2-1 */
        $nsis = Nsi::where('ish_version_id',$dpp->ish_version_id)
        ->join('nsi_types', 'nsis.type_id', '=', 'nsi_types.id')
        ->where('nsi_types.part',1)
        ->orderBy('nsi_types.position')
        ->select('nsis.nsiFullName')
        ->get();
        // if ($nsis->count() > 0)
        // {
        //     $part2_pos++;
        //     $table->addRow(null,array('tblHeader' => false));
        //     $table->addCell(9530)->addText("2.".$part2_pos." Нормативные правовые акты, нормативная техническая документация, иная документация",$tableNormalFont,$cellNoSpace);
        // }
        $nsi_pos = 0;
        foreach($nsis as $nsi)
        {
            $nsi_pos++;
            $nsi_name = str_replace(' "',' «',$nsi->nsiFullName);
            $nsi_name = str_replace('" ','» ',$nsi_name);
            $table->addRow(null,array('tblHeader' => false));
            $table->addCell(9530)->addText("2.".$nsi_pos." ".$nsi_name,$tableNormalFont,$cellNoSpaceJustify);
        }
        /* НСИ 2-2 */
        $nsis = Nsi::where('ish_version_id',$dpp->ish_version_id)
        ->join('nsi_types', 'nsis.type_id', '=', 'nsi_types.id')
        ->where('nsi_types.part',2)
        ->orderBy('nsi_types.position')
        ->select('nsis.nsiFullName')
        ->get();
        // if ($nsis->count() > 0)
        // {
        //     $part2_pos++;
        //     $table->addRow(null,array('tblHeader' => false));
        //     $table->addCell(9530)->addText("2.".$part2_pos." Учебники, монографии",$tableNormalFont,$cellNoSpace);
        // }
        foreach($nsis as $nsi)
        {
            $nsi_pos++;
            $nsi_name = str_replace(' "',' «',$nsi->nsiFullName);
            $nsi_name = str_replace('" ','» ',$nsi_name);
            $table->addRow(null,array('tblHeader' => false));
            $table->addCell(9530)->addText("2.".$nsi_pos." ".$nsi_name,$tableNormalFont,$cellNoSpaceJustify);
        }
        /* НСИ 3 */
        $nsis = Nsi::where('ish_version_id',$dpp->ish_version_id)
        ->join('nsi_types', 'nsis.type_id', '=', 'nsi_types.id')
        ->where('nsi_types.part',3)
        ->orderBy('nsi_types.position')
        ->select('nsis.nsiFullName')
        ->get();

        // if ($nsis->count() > 0)
        // {
        //     $part2_pos++;
        //     $table->addRow(null,array('tblHeader' => false));
        //     $table->addCell(9530)->addText("3. Интернет ресурсы",$tableBoldFont,$cellHCenteredNoSpace);
        // }
        foreach($nsis as $key=>$nsi)
        {
            $nsi_pos++;
            $text = str_replace('&','&#38;',$nsi->nsiFullName);
            $text = str_replace('<','&#60;',$text);
            $text = str_replace('>','&#62;',$text);
            $text = str_replace(' "',' «',$text);
            $text = str_replace('" ','» ',$text);
            $table->addRow(null,array('tblHeader' => false));
            $table->addCell(9530)->addText("2.".$nsi_pos." ".$text,$tableNormalFont,$cellNoSpaceJustify);
        }
        $table->addRow(null,array('tblHeader' => false));
        $table->addCell(9530)->addText("3 Информационное обеспечение",$tableBoldFont,$cellHCenteredNoSpace);
        $table->addRow(null,array('tblHeader' => false));
        $table->addCell(9530)->addText("3.1 http://library.miit.ru/ ",$tableNormalFont,$cellNoSpace);

        return $table;
    }

    public function get_image_width_height($path)
    {
        $image_size = getimagesize('storage/'.$path);
        $result = array(
            'width' => $image_size[0],
            'height' => $image_size[1],
            'path' => 'storage/'.$path
        );
        if ($image_size[0] > 300)
        {
            $result['width'] = 300;
            //$result['height'] = 570*$image_size[1] / $image_size[0];
            //dd($result);
        }
        return $result;
    }
}
