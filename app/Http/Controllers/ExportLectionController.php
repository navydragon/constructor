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
use App\Signatory;
use App\Task;
use \PhpOffice\PhpWord\PhpWord;
use \PhpOffice\PhpWord\Style\Language;
use File;
use \DocxMerge\DocxMerge;
use Com;
use Illuminate\Support\Facades\Auth;

class ExportLectionController extends Controller
{

    public function export_lection(Dpp $dpp)
    {
        if ((!is_null($dpp->category)) && ($dpp->category->name == 'РОСДОРНИИ' ))
        {
            return $this->export_lection_rdn($dpp);
        }

        $tableStyle = array( 'borderColor' => '000000', 'borderSize'  => 1, 'cellMarginLeft' => 107.716535433,'cellMarginRight' => 107.716535433, 'cellMarginTop'  => 0,'cellMarginBottom'  => 0,'width'=> '100%');
        $tableWOBordersStyle = array('cellMargin'  => 50,'width'=> '100%');
        $tableNormalFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 12, 'bold' => false);
        $tableBoldFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 12, 'bold' => true);
        $cellHCenteredNoSpace = array('align' => 'center','lineHeight' => 1,'spaceAfter' => 0,'indentation'=> ['firstLine' => 0]);
        $cellNoSpace = array('spaceAfter' => 0,'lineHeight' => 1,'indentation'=> ['firstLine' => 0]);
        $firstRowStyle = array();
        $lection_mtos = Mto::where('is_base_for_lection',true)->where('dpp_id','=',$dpp->id)->get();
        $mergeArr = [];
        $t = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('/templates/make_lection_miit.docx'));
        \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true);

        $dpp->st_version->remake_positions();

        $t->setValue('dppName', $dpp->name);
        if ($dpp->dpp_type_id == 1) {$dppType = 'ПРОГРАММЫ ПОВЫШЕНИЯ КВАЛИФИКАЦИИ';}else{$dppType = 'ПРОГРАММЫ ПРОФЕССИОНАЛЬНОЙ ПЕРЕПОДГОТОВКИ';}
        $t->setValue('dppType',$dppType);
        $t->setValue('year', $dpp->year);
        $pathToSave = storage_path('/temp/start.docx');  $t->saveAs($pathToSave);
        //array_push($mergeArr,$pathToSave);

        $sections = StructureSection::where('st_version_id',$dpp->st_version_id)->where('parent_id',null)->where('name','<>','Итоговая аттестация')->orderBy('position')->get();
        //->where('position','<',4)
        $section_id = null;
        foreach ($sections as $key => $section)
        {
            $t = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('/templates/make_lection2_miit.docx'));
            \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true);
            $t->setValue('sectionName',"Раздел ".$section->position." ".$section->name);
            $t->setValue('mtoNum',$section->position.".1");
            $pathToSave = storage_path('/temp/section_'.$section->id.'.docx');  $t->saveAs($pathToSave);array_push($mergeArr,$pathToSave);

            $lections = StructureSection::where('parent_id',$section->id)->orderBy('position')->get();
            foreach ($lections as $key_lec => $lection)
            {
                $t = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('/templates/make_lection3_miit.docx'));
                $t->setValue('lectionName',"Лекция ".$section->position.".".$lection->position." ".$lection->name);
                $t->setValue('mtoNum',$section->position.".1");
                $knowledge = Knowledge::find($lection->knowledge_id);
                $t->setValue('knowledge',$knowledge->name);
                $t->setValue('lectionHours',$lection->lection_hours);
                $t->setValue('nsiNum',$section->position.".".$lection->position.".1");
                $t->setComplexBlock('nsiTable', $this->get_nsi_table($dpp,$lection));

                $t->setValue('lectionHours',$lection->lection_hours);
                $pathToSave = storage_path('/temp/lection_'.$lection->id.'.docx');  $t->saveAs($pathToSave);array_push($mergeArr,$pathToSave);


                $lectionContent = Lection::where('section_id',$lection->id)->where('type','lec')->get()->first();
                if ($lectionContent && ($lectionContent->is_loaded))
                {
                    $pathToSave = storage_path('app/lections/'.$lectionContent->id.'/Лекция.docm');array_push($mergeArr,$pathToSave);
                }
            }
        }

        $code = $this->generateRandomFileName();
        $pathToSave = storage_path('temp/ПрДПП_'.$dpp->abbreveation.'_Приложение_Б._Конспект_лекций_'.$code.'.docx');
        //$pathToSaveTemp = storage_path('temp/test_lec_'.$dpp->id.'_'.Auth::id().'.docx');

        $pathToSaveTemp = storage_path('temp/'.$code.'.docx');
        if ($dpp->id != 0)
        {
            $dm = new DocxMerge();
           // array_unshift($mergeArr,storage_path('/temp/start.docx'));
            $dm->merge( $mergeArr , $pathToSaveTemp );
        }else{
            $merge = new \Phpdocx\Utilities\MultiMerge();
            $merge->mergeDocx(storage_path('/temp/start.docx'),$mergeArr, storage_path('/temp/ПрДПП_'.$dpp->abbreveation.'_Приложение Б. Конспект лекций.docx'), array('renameStyles' => true,'mergeType'=>1));
        }


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

        rename ($pathToSaveTemp, $pathToSave);

        return response()->download($pathToSave);

        $word = new COM("Word.Application") or die("Unable to instantiate Word");
        $doc = $word->Documents->Open($pathToSaveTemp);


        $word->Run('UpdateFooter');
        $doc->Save();
        $doc->Close();

        // Закрытие Word
        $word->Quit();
        //$word = null;

        // // TOC
        // $t = new \Phpdocx\Create\CreateDocxFromTemplate($pathToSaveTemp);
        // $t->setTemplateSymbol('${', '}');
        // $toc = new \Phpdocx\Elements\WordFragment($t);
        // $legend = array(
        //     'text' => 'Щелкните здесь, чтобы обновить содержание',
        //     'color' => '000000',
        //     'bold' => false,
        //     'fontSize' => 14,
        // );
        // $toc->addTableContents(array('autoUpdate'=>true),$legend);
        // $t->replaceVariableByWordFragment(array('TOC' => $toc), array('type' => 'block'));
        // $t->createDocx($pathToSaveTemp);

        // rename ($pathToSaveTemp, $pathToSave);
        // return response()->download($pathToSave);
    }

    public function export_lection_rdn (Dpp $dpp)
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
        \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true);
        $t->setValue('dppName', $dpp->name);
        if ($dpp->dpp_type_id == 1) {$dppType = 'программы повышения квалификации';}else{$dppType = 'программы профессиональной переподготовки';}
        $t->setValue('dppType',$dppType);
        $t->setValue('year', $dpp->year);
        $pathToSave = storage_path('/temp/start.docx');  $t->saveAs($pathToSave);
        //array_push($mergeArr,$pathToSave);

        $sections = StructureSection::where('st_version_id',$dpp->st_version_id)->where('parent_id',null)->where('name','<>','Итоговая аттестация')->orderBy('position')->get();
        $section_id = null;
        foreach ($sections as $key => $section)
        {
            //  if ($key == 0)
            //  {
                $t = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('/templates/make_lection2.docx'));
                \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true);
                $t->setValue('sectionName',"Раздел ".$section->position.". ".$section->name);
                $t->setValue('mtoNum',$section->position.".1");
                $pathToSave = storage_path('/temp/section_'.$section->id.'.docx');  $t->saveAs($pathToSave);array_push($mergeArr,$pathToSave);

                $lections = StructureSection::where('parent_id',$section->id)->orderBy('position')->get();
                foreach ($lections as $lection)
                {
                    $t = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('/templates/make_lection3.docx'));
                    $t->setValue('lectionName',"Лекция ".$section->position.".".$lection->position.". ".$lection->name);
                    $t->setValue('mtoNum',$section->position.".1");
                    $knowledge = Knowledge::find($lection->knowledge_id);
                    $t->setValue('knowledge',$knowledge->name);
                    $t->setValue('lectionHours',$lection->lection_hours);
                    $t->setValue('nsiNum',$section->position.".".$lection->position.".1");
                    $t->setComplexBlock('nsiTable', $this->get_nsi_table($dpp,$lection));

                    $t->setValue('lectionHours',$lection->lection_hours);
                    $pathToSave = storage_path('/temp/lection_'.$lection->id.'.docx');  $t->saveAs($pathToSave);array_push($mergeArr,$pathToSave);



                    //$pathToSave = storage_path('/temp/lection_'.$lection->id.'.docx');  $t->saveAs($pathToSave);array_push($mergeArr,$pathToSave);
                    $lectionContent = Lection::where('section_id',$lection->id)->where('type','lec')->get()->first();
                    if ($lectionContent && ($lectionContent->is_loaded))
                    {
                        //$pathToSave = storage_path('app/lections/'.$lectionContent->id.'/Лекция.docm');array_push($mergeArr,$pathToSave);



                       // $t = new \Phpdocx\Create\CreateDocxFromTemplate(storage_path('app/lections/'.$lectionContent->id.'/Лекция.docm'));
                       // $t->createDocx(storage_path('/temp/temp_lection_'.$lection->id.'.docx'));
                       // array_push($mergeArr,storage_path('/temp/temp_lection_'.$lection->id.'.docx'));
                        //$lectionArr=[storage_path('app/lections/'.$lectionContent->id.'/Лекция.docm')];
                        //$merge = new \Phpdocx\Utilities\MultiMerge();
                        //$merge->mergeDocx($pathToSave,$lectionArr, $pathToSave, array('renameStyles' => true));
                    }
                }
           // }
        }
        // $dm = new DocxMerge();
        // $dm->merge( $mergeArr , storage_path('/temp/ПрДПП_'.$dpp->abbreveation.'_Приложение Б. Конспект лекций.docx') );

        $merge = new \Phpdocx\Utilities\MultiMerge();
        $merge->mergeDocx(storage_path('/temp/start.docx'),$mergeArr, storage_path('/temp/ПрДПП_'.$dpp->abbreveation.'_Приложение Б. Конспект лекций.docx'), array('renameStyles' => true));

        $t = new \Phpdocx\Create\CreateDocxFromTemplate(storage_path('/temp/ПрДПП_'.$dpp->abbreveation.'_Приложение Б. Конспект лекций.docx'));
        $t->setTemplateSymbol('${', '}');

        $toc = new \Phpdocx\Elements\WordFragment($t);
        $legend = array(
            'text' => 'Щелкните здесь, чтобы обновить содержание',
            'color' => '000000',
            'bold' => false,
            'fontSize' => 14,
        );
        $toc->addTableContents(array('autoUpdate'=>true),$legend,storage_path('/templates/TOC_sample.docx'));

        $t->replaceVariableByWordFragment(array('TOC' => $toc), array('type' => 'block'));
        $t->createDocx(storage_path('/temp/ПрДПП_'.$dpp->abbreveation.'_Приложение Б. Конспект лекций.docx'));
        return response()->download(storage_path('temp/ПрДПП_'.$dpp->abbreveation.'_Приложение Б. Конспект лекций.docx'));
    }

    public function get_nsi_table(Dpp $dpp, StructureSection $theme)
    {

        $tableStyle = array( 'borderColor' => '000000', 'borderSize'  => 1, 'cellMarginLeft' => 107.716535433,'cellMarginRight' => 107.716535433, 'cellMarginTop'  => 0,'cellMarginBottom'  => 0,'width'=> '100%');
        $tableWOBordersStyle = array('cellMargin'  => 50,'width'=> '100%');
        $tableNormalFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 12, 'bold' => false);
        $tableBoldFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 12, 'bold' => true);
        $cellHCenteredNoSpace = array('align' => 'center','lineHeight' => 1,'spaceAfter' => 0,'indentation'=> ['firstLine' => 0]);
        $cellNoSpace = array('spaceAfter' => 0,'lineHeight' => 1,'indentation'=> ['firstLine' => 0]);
        $cellNoSpaceSecond = array('spaceAfter' => 0,'lineHeight' => 1,'indentation'=> ['firstLine' => 170]);
        $firstRowStyle = array();

        $knowledges = $theme->knowledges->pluck('id')->toArray();
        $table = new \PhpOffice\PhpWord\Element\Table($tableStyle);
        $table->addRow(null,array('tblHeader' => false));
        $table->addCell(9530)->addText("1 Учебно-методическая документация",$tableBoldFont,$cellHCenteredNoSpace);
        $table->addRow(null,array('tblHeader' => false));
        $table->addCell(9530)->addText("1.1 Конспект лекций",$tableNormalFont,$cellNoSpace);

        $part_pos = 1; $part2_pos = 0;

        $knowledge_nsis = Nsi::join('knowledge_nsis', 'nsis.id', '=', 'knowledge_nsis.nsi_id')
        ->join('nsi_types', 'nsis.type_id', '=', 'nsi_types.id')
        ->where('knowledge_nsis.knowledge_id',$knowledges)
        ->select('nsis.nsiFullName','nsi_types.part','nsi_types.position')->get();

        $nsis_all = $knowledge_nsis->sortBy('position');
        $nsis_2_1 = $nsis_all->filter(function($value, $key) {if ($value['part'] == 1) {return true;}})->all();
        $nsis_2_2 = $nsis_all->filter(function($value, $key) {if ($value['part'] == 2) {return true;}})->all();
        if (count($nsis_2_1)+count($nsis_2_2) > 0)
        {
            $part_pos++;
            $table->addRow(null,array('tblHeader' => false));
            $table->addCell(9530)->addText($part_pos." Литература",$tableBoldFont,$cellHCenteredNoSpace);
        }
        // /* НСИ 2-1 */
        $nsis = $nsis_2_1;
        if (count($nsis) > 0)
        {
            $part2_pos++;
            $table->addRow(null,array('tblHeader' => false));
            $table->addCell(9530)->addText($part_pos.".".$part2_pos." Нормативные правовые акты, нормативная техническая документация, иная документация",$tableNormalFont,$cellNoSpace);
        }
        $nsi_pos = 0;
        foreach($nsis as $key=>$nsi)
        {
            $nsi_pos ++;
            $table->addRow(null,array('tblHeader' => false));
            $table->addCell(9530)->addText($part_pos.".".$part2_pos.".".$nsi_pos." ".$nsi->nsiFullName,$tableNormalFont,$cellNoSpaceSecond);
        }
        // /* НСИ 2-2 */
        $nsis = $nsis_2_2;
        if (count($nsis) > 0)
        {
            $part2_pos++;
            $table->addRow(null,array('tblHeader' => false));
            $table->addCell(9530)->addText($part_pos.".".$part2_pos." Учебники, монографии",$tableNormalFont,$cellNoSpace);
        }
        $nsi_pos = 0;
        foreach($nsis as $key=>$nsi)
        {
            $nsi_pos ++;
            $table->addRow(null,array('tblHeader' => false));
            $table->addCell(9530)->addText($part_pos.".".$part2_pos.".".$nsi_pos." ".$nsi->nsiFullName,$tableNormalFont,$cellNoSpaceSecond);
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
            $table->addCell(9530)->addText($part_pos.".".$nsi_pos." ".$nsi->nsiFullName,$tableNormalFont,$cellNoSpace);
        }
        $part_pos++;
        $table->addRow(null,array('tblHeader' => false));
        $table->addCell(9530)->addText($part_pos." Электронно-библиотечная система",$tableBoldFont,$cellHCenteredNoSpace);
        $table->addRow(null,array('tblHeader' => false));
        $table->addCell(9530)->addText($part_pos.".1 Определяется образовательной организацией ",$tableNormalFont,$cellNoSpace);
        return $table;
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
