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
use App\Signatory;
use \PhpOffice\PhpWord\PhpWord;
use \PhpOffice\PhpWord\Style\Language;
use File;
use \DocxMerge\DocxMerge;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\Reader\Word2007;
use \PhpOffice\PhpWord\Element\TextRun;
use Com;

class ExportPracticeController extends Controller
{
    public function export_practice(Dpp $dpp)
    {

        $dpp->st_version->remake_positions();

        if ((!is_null($dpp->category)) && ($dpp->category->name == 'РОСДОРНИИ' ))
        {
            return $this->export_practice_rdn($dpp);
        }

        $tableStyle = array( 'borderColor' => '000000', 'borderSize'  => 1, 'cellMarginLeft' => 107.716535433,'cellMarginRight' => 107.716535433, 'cellMarginTop'  => 0,'cellMarginBottom'  => 0,'width'=> '100%');
        $tableWOBordersStyle = array('cellMargin'  => 50,'width'=> '100%');
        $tableNormalFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 12, 'bold' => false);
        $tableBoldFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 12, 'bold' => true);
        $cellHCenteredNoSpace = array('align' => 'center','lineHeight' => 1,'spaceAfter' => 0,'indentation'=> ['firstLine' => 0]);
        $cellNoSpace = array('spaceAfter' => 0,'lineHeight' => 1,'indentation'=> ['firstLine' => 0]);
        $firstRowStyle = array();
        $mergeArr = [];
        $t = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('/templates/make_practice_miit.docx'));
        \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true);


        $t->setValue('dppName', $dpp->name);

        if ($dpp->dpp_type_id == 1) {$dppType = 'ПРОГРАММЫ ПОВЫШЕНИЯ КВАЛИФИКАЦИИ';}else{$dppType = 'ПРОГРАММЫ ПРОФЕССИОНАЛЬНОЙ ПЕРЕПОДГОТОВКИ';}
        $t->setValue('dppType',$dppType);
        $t->setValue('year', $dpp->year);
        $pathToSave = storage_path('/temp/start.docx');  $t->saveAs($pathToSave);
        //array_push($mergeArr,$pathToSave);
        $sections = StructureSection::where('st_version_id',$dpp->st_version_id)->where('parent_id',null)->orderBy('position')->get();
        $section_id = null;
        $section_counter = 1;
        foreach ($sections as $section)
        {
            $theme_counter = 1;
            $themes = StructureSection::where('parent_id',$section->id)->orderBy('position')->get();
            foreach ($themes as $theme)
            {
                if ($theme->practice_hours > 0)
                {
                    $t = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('/templates/make_practice2.docx'));
                    $inline = new \PhpOffice\PhpWord\Element\TextRun();
                    if ($section->id == $section_id)
                    {
                        $t->setComplexBlock('sectionName', $inline);
                    }else{
                        $t->setValue('sectionName',$section_counter.". Раздел ".$section->position.". ".$section->name);
                        $section_id = $section->id;
                    }
                    $t->setValue('lectionName',$section_counter.".".$theme_counter.". Тема ".$section->position.".".$theme->position." ".$theme->name);
                    $t->setValue('practiceHours',$theme->practice_hours);

                /* РЕЗУЛЬТАТЫ */
                $t->setValue('resultsNum',$section_counter.".".$theme_counter.".1");
                $t->setComplexBlock('resultsTable', $this->get_results_table($dpp,$theme));

                /* МТО */
                $t->setValue('mtoNum',$section_counter.".".$theme_counter.".2");

                $t->setComplexBlock('mtoTable', $this->get_mto_table($dpp,$theme));
                /* КОНЕЦ МТО */
                /* НСИ */
                 $t->setValue('nsiNum',$section_counter.".".$theme_counter.".3");
                 $t->setComplexBlock('nsiTable', $this->get_nsi_table($dpp,$theme));
                /* КОНЕЦ НСИ */
                $pathToSave = storage_path('/temp/practice_'.$theme->id.'.docx');  $t->saveAs($pathToSave);array_push($mergeArr,$pathToSave);

                $practice = Lection::where('section_id',$theme->id)->where('type','pr')->get()->first();
                if ($practice)
                {
                    $af = $practice->additional_files;
                    if ($af->count() > 0)
                    {
                        $t = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('/templates/make_dop_mat.docx'));
                        $t->cloneRow('dm_name', $af->count());
                        foreach ($af as $key=>$value)
                        {
                            $num = $key+1;
                            $t->setValue('dm_name#'.$num, $value->name);
                            $t->setValue('dm_url#'.$num, 'https://constructor-api.emiit.ru/content/'.$practice->id.'/additional_files/'.$value->id.'/download');

                        }
                        $pathToSave = storage_path('/temp/lection_'.$theme->id.'_materials.docx');  $t->saveAs($pathToSave);array_push($mergeArr,$pathToSave);
                    }
                }

                $practiceContent = Lection::where('section_id',$theme->id)->where('type','pr')->get()->first();
                if ($practiceContent && ($practiceContent->is_loaded))
                {
                    $pathToSave = storage_path('app/lections/'.$practiceContent->id.'/Практическое_занятие.docm');
                    // $docx = new \Phpdocx\Create\CreateDocxFromTemplate($pathToSave);
                    // $docx->removeFooters();
                    // $docx->createDocx($pathToSave);
                    array_push($mergeArr,$pathToSave);
                }

                $theme_counter++;
                }
            }
            if ($theme_counter > 1)
            {
                $section_counter++;
            }

        }

        //$dm = new DocxMerge();
        //$dm->merge( $mergeArr , storage_path('/temp/ПрДПП_'.$dpp->abbreveation.'_Приложение В. Методические указания к ПЗ.docx') );
        $code = $this->generateRandomFileName();
        $pathToSave = storage_path('temp/ПрДПП_'.$dpp->abbreveation.'_Приложение В. Методические указания к ПЗ_'.$code.'.docx');
        $pathToSaveTemp = storage_path('temp/'.$code.'.docx');
        //$pathToSaveTemp = 'E:/test.docx';

        if ($dpp->id != 0)
        {
            $dm = new DocxMerge();
            //array_unshift($mergeArr,storage_path('/temp/start.docx'));
            $dm->merge( $mergeArr , $pathToSaveTemp);

        }else{
            $merge = new \Phpdocx\Utilities\MultiMerge();
            $merge->mergeDocx(storage_path('/temp/start.docx'),$mergeArr, storage_path('/temp/ПрДПП_'.$dpp->abbreveation.'_Приложение В. Методические указания к ПЗ.docx'), array('renameStyles' => true,'mergeType'=>1));
        }


        //start_section

        $t = new \Phpdocx\Create\CreateDocxFromTemplate(storage_path('/temp/start.docx'));
        $t->setTemplateSymbol('${', '}');
        $toc = new \Phpdocx\Elements\WordFragment($t);
        $legend = array(
            'text' => 'Щелкните здесь, чтобы обновить содержание',
            'color' => '000000',
            'bold' => false,
            'fontSize' => 14,
        );
        $toc->addTableContents(array('autoUpdate'=>true),$legend);
        $t->replaceVariableByWordFragment(array('TOC' => $toc), array('type' => 'block'));
        $t->createDocx(storage_path('/temp/start.docx'));

        $dm = new DocxMerge();
        array_unshift($mergeArr,storage_path('/temp/start.docx'));
        $dm->merge( $mergeArr , $pathToSaveTemp);


//         $word = new COM("Word.Application") or die("Unable to instantiate Word");
//         $doc = $word->Documents->Open($pathToSaveTemp);
//         $word->Run('UpdateFooter');
//         $doc->Save();
//         $doc->Close();
//         $word->Quit();
//         $word = null;

        rename ($pathToSaveTemp, $pathToSave);

        return response()->download($pathToSave);

        // $merge = new \Phpdocx\Utilities\MultiMerge();
        // $merge->mergeDocx(storage_path('/temp/start.docx'),$mergeArr, $pathToSave, array());



        //
        //

        // $sections = $doc->Sections;
        //
        //
        //

        // Закрытие Word
        //
        // $word = null;

        //$phpWord = new PhpWord();


        // $docx = new \Phpdocx\Create\CreateDocxFromTemplate($pathToSaveTemp);
        // $docx->removeFooters();
        // $docx->createDocx($pathToSaveTemp);

        // $docx = new \Phpdocx\Create\CreateDocxFromTemplate($pathToSaveTemp);
        // $numbering = new \Phpdocx\Elements\WordFragment($docx, 'defaultFooter');
        // $options = array(
        //     'textAlign' => 'center',
        //     'bold' => false,
        //     'sz' => 12,
        //     'color' => '000000',
        // );
        // $numbering->addPageNumber('numerical', $options);
        // $docx->addFooter(array('default' => $numbering));
        // $docx->createDocx($pathToSaveTemp);


        // $phpWord = new \PhpOffice\PhpWord\TemplateProcessor($pathToSaveTemp);
        // $set = $phpWord->getSettings();
        // dd($set);
        // $sections = $phpWord->getSections();
        // for ($i = 1; $i < count($sections); $i++) {
        //     // Удаление колонтитулов в нижнем колонтитуле
        //     $footer = $sections->getFooter($i);
        //     $footer->clear();

        //     // Добавление номера страницы в нижний колонтитул
        //     $pageNumberParagraph = $footer->addParagraph();
        //     $pageNumberRun = $pageNumberParagraph->addPageNumber();
        //     $pageNumberRun->getFontStyle()->setSize(12); // Размер шрифта
        //     $pageNumberParagraph->getParagraphStyle()->setAlignment(Jc::CENTER); // Выравнивание по центру
        // }
        // $phpWord->save($pathToSaveTemp);

        // $footerPage = new \Phpdocx\Elements\WordFragment($docx, 'defaultFooter');
        // $options = array(
        //     'textAlign' => 'center',
        //     'bold' => false,
        //     'sz' => 12,
        //     'color' => '000000',
        // );

        // $footerPage->addPageNumber('numerical', $options);
        // $docx->addFooter(array('default' => $footerPage));



        // TOC


        // $word = new COM("Word.Application") or die("Unable to instantiate Word");
        // $doc = $word->Documents->Open($pathToSaveTemp);
        // $word->Run('UpdateFooter');
        // $doc->Save();
        // $doc->Close();
        // $word->Quit();
        // $word = null;

        // $t = new \Phpdocx\Create\CreateDocxFromTemplate($pathToSaveTemp);
        // $t->createDocx($pathToSaveTemp);
        // $pythonScriptPath = base_path('app/Http/Controllers/test.py');
        // $escapedFileToProcess = escapeshellarg($pathToSaveTemp);
        // $output = shell_exec("python $pythonScriptPath $escapedFileToProcess");
        // dd($output);

    }



    public function get_mto_table(Dpp $dpp, StructureSection $theme)
    {
        $tableStyle = array( 'borderColor' => '000000', 'borderSize'  => 1, 'cellMarginLeft' => 107.716535433,'cellMarginRight' => 107.716535433, 'cellMarginTop'  => 0,'cellMarginBottom'  => 0,'width'=> '100%');
        $tableWOBordersStyle = array('cellMargin'  => 50,'width'=> '100%');
        $tableNormalFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 12, 'bold' => false);
        $tableBoldFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 12, 'bold' => true);
        $cellHCenteredNoSpace = array('align' => 'center','lineHeight' => 1,'spaceAfter' => 0,'indentation'=> ['firstLine' => 0]);
        $cellNoSpace = array('spaceAfter' => 0,'lineHeight' => 1,'indentation'=> ['firstLine' => 0]);
        $cellNoSpaceLeft = array('align' => 'left','spaceAfter' => 0,'lineHeight' => 1,'indentation'=> ['firstLine' => 0]);
        $firstRowStyle = array();
        $table = new \PhpOffice\PhpWord\Element\Table($tableStyle);
        $practice_mtos = Mto::where('is_base_for_practice',true)->where('dpp_id','=',$dpp->id);
        $theme_specials = Mto::distinct()
            ->select('mtos.*')
            ->join('task_mtos', 'mtos.id', '=', 'task_mtos.mto_id')
            ->join('task_subjects', 'task_subjects.task_id', '=', 'task_mtos.task_id')
            ->join('abilities', 'abilities.id', '=', 'task_subjects.ability_id')
            ->join('ability_section', 'ability_section.ability_id', '=', 'abilities.id')
            ->join('structure_sections', 'structure_sections.id', '=', 'ability_section.section_id')
            ->where('structure_sections.id', '=', $theme->id)
            ->where('mtos.is_base_for_lection', '=', 0)
            ->where('mtos.is_base_for_practice', '=', 0);
        $practice_mtos = $practice_mtos->union($theme_specials)->get();
        $parent_types= MtoType::where('parent_id','=',null)->get();
        $f_n = 0;
        foreach ($parent_types as $parent_type)
        {
            $children_types =  MtoType::where('parent_id','=',$parent_type->id)->get();
            $this_mtos = $practice_mtos->where('type_id','=',$parent_type->id)->sortBy('position');
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
                    $name = $mto->name;
                    $name = mb_strtolower(mb_substr($name, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($name, 1, mb_strlen($name), 'UTF-8');
                    $table->addRow();
                    $table->addCell()->addText($f_n.".".$s_n." ".$name,$tableNormalFont,$cellNoSpaceLeft);
                    $table->addCell()->addText( $mto->quantity,$tableNormalFont,$cellHCenteredNoSpace);
                    $table->addCell()->addText( $mto->measure,$tableNormalFont,$cellHCenteredNoSpace);
                    $table->addCell()->addText( $mto->note,$tableNormalFont,$cellNoSpaceLeft);
            }

            foreach ($children_types as $children_type)
            {
                $this_mtos = $practice_mtos->where('type_id','=',$children_type->id)->sortBy('position');
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
                    $name = $mto->name;
                    $name = mb_strtolower(mb_substr($name, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($name, 1, mb_strlen($name), 'UTF-8');
                    $table->addRow();
                    $table->addCell()->addText($f_n.".".$s_n.".".$t_n." ".$name,$tableNormalFont,$cellNoSpaceLeft);
                    $table->addCell()->addText( $mto->quantity,$tableNormalFont,$cellHCenteredNoSpace);
                    $table->addCell()->addText( $mto->measure,$tableNormalFont,$cellHCenteredNoSpace);
                    $table->addCell()->addText( $mto->note,$tableNormalFont,$cellNoSpaceLeft);
                }
            }
        }
        return $table;
    }

    public function get_nsi_table(Dpp $dpp, StructureSection $theme)
    {
        $tableStyle = array( 'borderColor' => '000000', 'borderSize'  => 1, 'cellMarginLeft' => 107.716535433,'cellMarginRight' => 107.716535433, 'cellMarginTop'  => 0,'cellMarginBottom'  => 0,'width'=> '100%');
        $tableWOBordersStyle = array('cellMargin'  => 50,'width'=> '100%');
        $tableNormalFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 12, 'bold' => false);
        $tableBoldFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 12, 'bold' => true);
        $cellHCenteredNoSpace = array('align' => 'center','lineHeight' => 1,'spaceAfter' => 0,'indentation'=> ['firstLine' => 0]);
        $cellNoSpace = array('spaceAfter' => 0,'lineHeight' => 1,'indentation'=> ['firstLine' => 0]);
        $firstRowStyle = array();

        $abilities = $theme->abilities->pluck('id')->toArray();
        $skills = $theme->skills->pluck('id')->toArray();

        $table = new \PhpOffice\PhpWord\Element\Table($tableStyle);
        $table->addRow(null,array('tblHeader' => false));
        $table->addCell(9530)->addText("1 Учебно-методическая документация",$tableBoldFont,$cellHCenteredNoSpace);
        $table->addRow(null,array('tblHeader' => false));
        $table->addCell(9530)->addText("1.1 Методические указания к организации и проведению практических занятий",$tableNormalFont,$cellNoSpace);
        $table->addRow(null,array('tblHeader' => false));
        $table->addCell(9530)->addText("2 Литература",$tableBoldFont,$cellHCenteredNoSpace);
        $part_pos = 2; $part2_pos = 0;
        /* НСИ 2-1 */
        // $nsis = $knowledge->nsis()->where('ish_version_id',$dpp->ish_version_id)
        // ->join('nsi_types', 'nsis.type_id', '=', 'nsi_types.id')
        // ->where('nsi_types.part',1)
        // ->orderBy('nsi_types.position')
        // ->select('nsis.nsiFullName')
        // ->get();
        $ability_nsis = Nsi::join('ability_nsis', 'nsis.id', '=', 'ability_nsis.nsi_id')
        ->join('nsi_types', 'nsis.type_id', '=', 'nsi_types.id')
        ->where('ability_nsis.ability_id',$abilities)
        ->select('nsis.nsiFullName','nsi_types.part','nsi_types.position')->get();
        $skill_nsis = Nsi::join('skill_nsis', 'nsis.id', '=', 'skill_nsis.nsi_id')
        ->join('nsi_types', 'nsis.type_id', '=', 'nsi_types.id')
        ->where('skill_nsis.skill_id',$skills)
        ->select('nsis.nsiFullName','nsi_types.part','nsi_types.position')->get();
        $nsis_all = $ability_nsis->union($skill_nsis)->sortBy('position');
        //dd($nsis->where('nsi_types.part',1));
        // /* НСИ 2-1 */
        $nsis = $nsis_all->filter(function($value, $key) {if ($value['part'] == 1) {return true;}})->all();
        if (count($nsis) > 0)
        {
            $part2_pos++;
            $table->addRow(null,array('tblHeader' => false));
            $table->addCell(9530)->addText("2.".$part2_pos." Нормативные правовые акты, нормативная техническая документация, иная документация",$tableNormalFont,$cellNoSpace);
        }
        $nsi_pos = 0;
        foreach($nsis as $key=>$nsi)
        {
            $nsi_pos ++;
            $table->addRow(null,array('tblHeader' => false));
            $table->addCell(9530)->addText("2.".$part2_pos.".".$nsi_pos." ".$nsi->nsiFullName,$tableNormalFont,$cellNoSpace);
        }
        // /* НСИ 2-2 */
        $nsis = $nsis_all->filter(function($value, $key) {if ($value['part'] == 2) {return true;}})->all();
        if (count($nsis) > 0)
        {
            $part2_pos++;
            $table->addRow(null,array('tblHeader' => false));
            $table->addCell(9530)->addText("2.".$part2_pos." Учебники, монографии",$tableNormalFont,$cellNoSpace);
        }
        $nsi_pos = 0;
        foreach($nsis as $key=>$nsi)
        {
            $nsi_pos ++;
            $table->addRow(null,array('tblHeader' => false));
            $table->addCell(9530)->addText("2.".$part2_pos.".".$nsi_pos." ".$nsi->nsiFullName,$tableNormalFont,$cellNoSpace);
        }
        // /* НСИ 3 */
        $nsis = $nsis_all->filter(function($value, $key) {if ($value['part'] == 3) {return true;}})->all();
        if (count($nsis) > 0)
        {
            $part_pos++;
            $table->addRow(null,array('tblHeader' => false));
            $table->addCell(9530)->addText($part_pos." Интернет ресурсы",$tableBoldFont,$cellHCenteredNoSpace);
        }
        $nsi_pos = 0;
        foreach($nsis as $key=>$nsi)
        {
            $nsi_pos ++;
            $table->addRow(null,array('tblHeader' => false));
            $table->addCell(9530)->addText("3.".$nsi_pos." ".$nsi->nsiFullName,$tableNormalFont,$cellNoSpace);
        }
        $part_pos++;
        $table->addRow(null,array('tblHeader' => false));
        $table->addCell(9530)->addText($part_pos." Электронно-библиотечная система",$tableBoldFont,$cellHCenteredNoSpace);
        $table->addRow(null,array('tblHeader' => false));
        $table->addCell(9530)->addText($part_pos.".1 Определяются образовательной организацией ",$tableNormalFont,$cellNoSpace);

        return $table;
    }

    public function get_results_table(Dpp $dpp, StructureSection $theme)
    {
        $tableStyle = array( 'borderColor' => '000000', 'borderSize'  => 1, 'cellMarginLeft' => 107.716535433,'cellMarginRight' => 107.716535433, 'cellMarginTop'  => 0,'cellMarginBottom'  => 0,'width'=> '100%');
        $tableWOBordersStyle = array('cellMargin'  => 50,'width'=> '100%');
        $tableNormalFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 12, 'bold' => false);
        $tableBoldFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 12, 'bold' => true);
        $cellHCenteredNoSpace = array('align' => 'center','lineHeight' => 1,'spaceAfter' => 0,'indentation'=> ['firstLine' => 0]);
        $cellNoSpace = array('spaceAfter' => 0,'lineHeight' => 1,'indentation'=> ['firstLine' => 0]);
        $firstRowStyle = array();

        $table = new \PhpOffice\PhpWord\Element\Table($tableStyle);
        $abilities = $theme->abilities;


        $knowledge = $theme->knowledges->first();
        $links = $knowledge->links;
        foreach ($links as $link) {
            $abilities[] = $link;
        }

        $abilities = $abilities->unique();

        $skills = $theme->skills;
        if (count($abilities) >0)
        {
            $table->addRow();
            $table->addCell(1565)->addText("Умения:",$tableNormalFont,$cellNoSpace);
            $cell = $table->addCell(7789);

            $ab_text = $abilities->pluck('name')->toArray();
            $breakStyle = array('lineHeight' => 1.5,'spaceAfter' => 6);
            $total = count($ab_text);
            foreach ($ab_text as $key=>$value)
            {
                if ($key==$total-1) {$sep='.';}else{$sep=';';}
                $cell->addText($value.$sep,$tableNormalFont,$cellNoSpace);

                //$cell->addText($sep,$tableNormalFont,$breakStyle);
                //$cell->addTextBreak(1,$tableNormalFont);
                // $textRun->addText($value,$tableNormalFont,$cellNoSpace);

                // if ($key!=$total-1)
                // {
                //     $textRun->addTextBreak(1,$tableNormalFont);
                // }
            }
            //dd($text);
            //$ab_text = implode("; ",$ab_text);
            //$table->addCell(7789)->addTextRun($text,$tableNormalFont,$cellNoSpace);


        }
        if (count($skills) >0)
        {
            $table->addRow();
            $table->addCell()->addText("Навыки:");
        }
        return $table;
    }

    public function export_practice_rdn(Dpp $dpp)
    {
        $tableStyle = array( 'borderColor' => '000000', 'borderSize'  => 1, 'cellMarginLeft' => 107.716535433,'cellMarginRight' => 107.716535433, 'cellMarginTop'  => 0,'cellMarginBottom'  => 0,'width'=> '100%');
            $tableWOBordersStyle = array('cellMargin'  => 50,'width'=> '100%');
            $tableNormalFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 12, 'bold' => false);
            $tableBoldFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 12, 'bold' => true);
            $cellHCenteredNoSpace = array('align' => 'center','lineHeight' => 1,'spaceAfter' => 0,'indentation'=> ['firstLine' => 0]);
            $cellNoSpace = array('spaceAfter' => 0,'lineHeight' => 1,'indentation'=> ['firstLine' => 0]);
            $firstRowStyle = array();
            $mergeArr = [];
            $t = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('/templates/make_practice.docx'));
            \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true);
            $t->setValue('dppName', $dpp->name);
            if ($dpp->dpp_type_id == 1) {$dppType = 'программы повышения квалификации';}else{$dppType = 'программы профессиональной переподготовки';}
            $t->setValue('dppType',$dppType);
            $t->setValue('year', $dpp->year);
            $pathToSave = storage_path('/temp/start.docx');  $t->saveAs($pathToSave);
            //array_push($mergeArr,$pathToSave);

            $sections = StructureSection::where('st_version_id',$dpp->st_version_id)->where('parent_id',null)->orderBy('position')->get();
            $section_id = null;
            foreach ($sections as $section)
            {
                $themes = StructureSection::where('parent_id',$section->id)->orderBy('position')->get();
                foreach ($themes as $theme)
                {
                    if ($theme->practice_hours > 0)
                    {
                        $t = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('/templates/make_practice2.docx'));
                        $inline = new \PhpOffice\PhpWord\Element\TextRun();
                        if ($section->id == $section_id)
                        {
                            $t->setComplexBlock('sectionName', $inline);
                        }else{
                            $t->setValue('sectionName',$section->position.". ".$section->name);
                            $section_id = $section->id;
                        }
                        $t->setValue('lectionName',$section->position.".".$theme->position.". ".$theme->name);
                        $t->setValue('practiceHours',$theme->practice_hours);

                    /* РЕЗУЛЬТАТЫ */
                    $t->setValue('resultsNum',$section->position.".".$theme->position.".1");
                    $t->setComplexBlock('resultsTable', $this->get_results_table($dpp,$theme));

                    /* МТО */
                    $t->setValue('mtoNum',$section->position.".".$theme->position.".2");

                    $t->setComplexBlock('mtoTable', $this->get_mto_table($dpp));
                    /* КОНЕЦ МТО */
                    /* НСИ */
                     $t->setValue('nsiNum',$section->position.".".$theme->position.".3");
                     $t->setComplexBlock('nsiTable', $this->get_nsi_table($dpp,$theme));
                    /* КОНЕЦ НСИ */
                    $pathToSave = storage_path('/temp/practice_'.$theme->id.'.docx');  $t->saveAs($pathToSave);array_push($mergeArr,$pathToSave);

                    $practice = Lection::where('section_id',$theme->id)->where('type','pr')->get()->first();
                    if ($practice)
                        {
                            $af = $practice->additional_files;
                            if ($af->count() > 0)
                            {
                                $t = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('/templates/make_dop_mat.docx'));
                                $t->cloneRow('dm_name', $af->count());
                                foreach ($af as $key=>$value)
                                {
                                    $num = $key+1;
                                    $t->setValue('dm_name#'.$num, $value->name);
                                    $t->setValue('dm_url#'.$num, 'https://constructor-api.emiit.ru/content/'.$practice->id.'/additional_files/'.$value->id.'/download');

                                }
                                $pathToSave = storage_path('/temp/lection_'.$theme->id.'_materials.docx');  $t->saveAs($pathToSave);array_push($mergeArr,$pathToSave);
                            }
                        }

                    $practiceContent = Lection::where('section_id',$theme->id)->where('type','pr')->get()->first();
                    if ($practiceContent && ($practiceContent->is_loaded))
                    {
                        $pathToSave = storage_path('app/lections/'.$practiceContent->id.'/Практическое_занятие.docm');array_push($mergeArr,$pathToSave);
                    }
                    }

                }
            }

            //$dm = new DocxMerge();
            //$dm->merge( $mergeArr , storage_path('/temp/ПрДПП_'.$dpp->abbreveation.'_Приложение В. Методические указания к ПЗ.docx') );
            $pathToSave = storage_path('temp/ПрДПП_'.$dpp->abbreveation.'_Приложение В. Методические указания к ПЗ.docx');
            $merge = new \Phpdocx\Utilities\MultiMerge();
            $merge->mergeDocx(storage_path('/temp/start.docx'),$mergeArr, $pathToSave, array('renameStyles' => true));

            $t = new \Phpdocx\Create\CreateDocxFromTemplate($pathToSave);
            $t->setTemplateSymbol('${', '}');
            // TOC
            $toc = new \Phpdocx\Elements\WordFragment($t);
            $legend = array(
                'text' => 'Щелкните здесь, чтобы обновить содержание',
                'color' => '000000',
                'bold' => false,
                'fontSize' => 14,
            );
            $toc->addTableContents(array('autoUpdate'=>true),$legend,storage_path('/templates/TOC_sample1.docx'));
            $t->replaceVariableByWordFragment(array('TOC' => $toc), array('type' => 'block'));
            $t->createDocx($pathToSave);


            return response()->download($pathToSave);
    }

    function generateRandomFileName() {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $length = 10;
        $randomFileName = '';

        for ($i = 0; $i < $length; $i++) {
            $randomFileName .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $randomFileName;
    }
}


