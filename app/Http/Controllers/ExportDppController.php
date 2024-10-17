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
use App\Designer;
use \PhpOffice\PhpWord\PhpWord;
use \PhpOffice\PhpWord\Style\Language;
use \PhpOffice\PhpWord\Element\TextRun;

class ExportDppController extends Controller
{
    public function export_dpp(Dpp $dpp)
    {
        if ((!is_null($dpp->category)) && ($dpp->category->name == 'РОСДОРНИИ' ))
        {
            return $this->export_dpp_rdn($dpp);
        }

        if ($dpp->is_digital == 1) {
            return $this->export_ck($dpp);
        }

       if ($dpp->dpp_type_id == 2) {
           return $this->export_pp($dpp);
       }


        $t = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('/templates/rut_template_char.docx'));
        \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true);

        $iv = $dpp->ish_version;
        $zv = $dpp->zun_version;
        $om = $dpp->om_version;

        //УТВЕЖДЕНИЕ
        $t->setValue('signatory_fio', $dpp->signatory_fio ?? "");
        $t->setValue('signatory_job', $dpp->signatory_job ?? "");
        //НАЗВАНИЕ ДПП
        $t->setValue('dppName', $dpp->name);

        //ГОД
        // $date = Carbon::now();
        // $year = Carbon::parse($dpp->created_at)->format('Y');
        $year = $dpp->year;
        $t->setValue('dppYear', $year);
        $t->setValue('signature_year', $year);

        //АННОТАЦИЯ
        $t->setValue('dppAnnotation', $iv->annotationDescription);

        //ИСПОЛНИТЕЛИ
        $designers = $dpp->designers;
        $t->cloneRow('job', count($designers));
        $index = 1;
        foreach ($designers as $key=>$designer)
        {
            $t->setValue('job#'.$index, $this->printRegalies($designer));
            $t->setValue('short_name#'.$index, $this->printFullname($designer));
            $t->setValue('work#'.$index, $designer->task);
            $index++;
        }

        /*ОСНОВАНИЯ*/
        $pss = $iv->prof_standarts;
        $vpos = $iv->fgoses()->where((function ($q) {$q->where("fgos_level_id","<>",1)->where("fgos_level_id","<>",5);}))->get();
        $spos = $iv->fgoses()->where((function ($q) {$q->where("fgos_level_id",1)->orWhere("fgos_level_id",5);}))->get();
        $ekses = $iv->ekses;
        $ektses = $iv->ektses;
        $count = 0;
        $arrays = [$pss, $vpos, $spos, $ekses, $ektses];
        foreach ($arrays as $array) { $count += (count($array) > 0) ? 1 : 0;}

        if ($count > 0)
        {
            $t->cloneBlock('block_basis', $count, true, true);
            $basis_index = 1;
        }else{
            $t->deleteBlock('block_basis');
        }

        /*ПРОФСТАНДАРТЫ */

        if (count($pss) > 0)
        {
            if (count($pss) == 1) {
                $text = 'Программа разработана на основе профессионального стандарта ';
                $value = $pss[0];
                $text .= $value->fullName.".";
            }else
            {
                $text = 'Программа разработана на основе профессиональных стандартов: ';
                foreach($pss as $key=>$value) {
                    $od = Carbon::parse($value->orderDate)->format('d.m.Y');
                    $rd = Carbon::parse($value->registrationDate)->format('d.m.Y');
                    if ($key != count($pss)-1)
                    {
                        $text .= $value->fullName.", ";
                    }else{
                        $text .= $value->fullName.".";
                    }
                }
            }
            $t->setValue('basisText#'.$basis_index, $text);
            $basis_index++;
        }
        $word = '';
        /* ФГОС ВПО */
        if (count($vpos) > 0)
        {
            if (count($vpos) == 1) {
                foreach($vpos as $key=>$value) {
                    if ($value->fgos_level_id == 1) {$word = '';}
                    if ($value->fgos_level_id == 2) {$word = ' (уровень бакалавриата)';}
                    if ($value->fgos_level_id == 3) {$word = ' (уровень специалитета)';}
                    if ($value->fgos_level_id == 4) {$word = ' (уровень магистратуры)';}
                    if ($value->fgos_level_id == 6) {$word = ' (уровень аспирантуры)';}
                    $text = 'Программа разработана на основе федерального государственного образовательного стандарта высшего образования ';
                    $value = $vpos[0];
                    // dd($value->requsites);
                    $text .= $value->code." ".$value->name.$value->requsites.".";
                    $t->setValue('basisText#'.$basis_index, $text);
                    $basis_index++;
                }
            }else{
                $text = 'Программа разработана на основе федеральных государственных образовательных стандартов высшего образования: ';
                foreach($vpos as $key=>$value) {
                    if ($value->fgos_level_id == 1) {$word = '';}
                    if ($value->fgos_level_id == 2) {$word = ' (уровень бакалавриата)';}
                    if ($value->fgos_level_id == 3) {$word = ' (уровень специалитета)';}
                    if ($value->fgos_level_id == 4) {$word = ' (уровень магистратуры)';}
                    if ($value->fgos_level_id == 6) {$word = ' (уровень аспирантуры)';}
                    if ($key != count($vpos)-1) {$ending=", "; }else{$ending=".";}
                    $text .= $value->code." ".$value->name.$value->requsites.$ending;

                }
                $t->setValue('basisText#'.$basis_index, $text);
                $basis_index++;
            }
        }
        /* ФГОС СПО */
        if (count($spos) > 0)
        {
            if (count($spos) == 1) {
                $text = 'Программа разработана на основе федерального государственного образовательного стандарта среднего профессионального образования ';
                $value = $spos[0];
               // dd($value);
                $text .= $value->code." ".$value->name.$value->requsites.".";
                $t->setValue('basisText#'.$basis_index, $text);
                $basis_index++;
            }else{
                $text = 'Программа разработана на основе федеральных государственных образовательных стандартов среднего профессионального образования: ';
                foreach($spos as $key=>$value) {
                    if ($key != count($spos)-1) {$ending=", "; }else{$ending=".";}
                    $text .= $value->code." ".$value->name.$value->requsites.$ending;
                }
                $t->setValue('basisText#'.$basis_index, $text);
                $basis_index++;
            }
        }
        /* ФГОС ЕКС/ЕКТС */
        $kvals = [];
        foreach($ekses as $eks) {array_push($kvals,$eks->full_name);}
        foreach($ektses as $ekts) {array_push($kvals,$ekts->full_name);}

        if (count($kvals) > 0)
        {

            if (count($kvals) == 1) {
                $text = 'Программа разработана на основе квалификационных требований по должности ';
                $value = $kvals[0];
                $text .= $kvals[0].".";
            }else{
                $text = 'Программа разработана на основе квалификационных требований по должностям: ';
                foreach($kvals as $key=>$value) {
                    if ($key != count($kvals)-1) {$ending=", "; }else{$ending=".";}
                    $text .= $value->name.$ending;
                }
                $t->setValue('basisText#'.$basis_index, $text);
                $basis_index++;
            }
        }
        //ЧАСЫ
        $t->setValue('hours', $dpp->total_hours);
        $t->setValue('academHours', $this->formatAcadem($dpp->total_hours));
        $t->setValue('hourCompletion', $this->formatHours($dpp->total_hours));
        $o_days = ceil($dpp->total_hours / 8);
        $z_days = ceil($dpp->total_hours / 4);

        $t->setValue('daysOch', $o_days);
        $t->setValue('ochCompletion', $this->formatDays($o_days));
        $t->setValue('daysZaoch', $z_days);
        $t->setValue('zaochCompletion', $this->formatDays($z_days));

        //ЦЕЛИ
        $comp_count = $dpp->competences()->count();
        if ($comp_count < 2)
        {
            $target = 'Целью обучения является совершенствование и (или) получение новой компетенции, необходимой для профессиональной деятельности.';
        }else{
            $target = 'Целью обучения является повышение профессионального уровня в рамках имеющейся у обучающегося квалификации.';
        }
        $t->setValue('target', $target);


        //ТРЕБОВАНИЯ К ОБРАЗОВАНИЮ, ТРЕБОВАНИЯ К КВАЛИФИКАЦИИ
        $pl = $iv->prof_levels;
        $edu_level_text = "";
        // foreach ($pl as $key=>$elem)
        // {
        //     if ($key != count($pl)-1)
        //     {
        //         $edu_level_text .= $elem->name."; ";
        //     }else{
        //         $edu_level_text .= $elem->name.".";
        //     }
        // }

        if (count($spos) == 0 && count($vpos) == 0)
        {
            $edu_level_text = 'лица, имеющие среднее профессиональное и (или) высшее образование; лица, получающие среднее профессиональное и (или) высшее образование';
        }

        if (count($spos) > 0 && count($vpos) == 0)
        {
            $edu_level_text = 'лица, имеющие среднее профессиональное и (или) высшее образование; лица, получающие среднее профессиональное и (или) высшее образование';
        }

        if (count($spos) == 0 && count($vpos) > 0)
        {
            $edu_level_text = 'лица, имеющие высшее образование; лица, получающие высшее образование';
        }

        $t->setValue('reqEdu', $edu_level_text);

        $t->setValue('reqQual', $iv->req_user_kval);
        $sv = StructureVersion::find($dpp->st_version_id);
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

        //УЧЕБНЫЙ ПЛАН

        $sect_count = StructureSection::where('st_version_id','=', $sv->id)->get()->count();
        $t->cloneRow('themeName', $sect_count);
        $sections = StructureSection::with(['knowledges' => function ($query) {}])
         ->with(['themes' => function ($query) {$query->orderBy('position');}])
         ->where('parent_id','=', null)
         ->where('st_version_id','=', $sv->id)
         ->orderBy('position')
         ->get();
         $number = 1;
        //  foreach ($sections as $sect)
        //  {

        //      foreach ($sect->themes as $theme)
        //      {
        //         $theme->zuns = (object)['knowledges'=>$theme->knowledges,'abilities'=>$theme->abilities,'skills'=>$theme->skills];
        //      }
        //  }
        $att_type = $dpp->att_type;
        $total = [0,0,0,0,0,0];
        foreach ($sections as $sect)
        {
            if ($sect->name == 'Итоговая аттестация')
            {
                if ($att_type == 'Зачет') {$att_text = ' в форме зачета';}
                if ($att_type == 'Экзамен') {$att_text = ' в форме экзамена';}
                $t->setValue('themeName#'.$number, $sect->position.". ".$sect->name.$att_text);
            }else{
                $t->setValue('themeName#'.$number, $sect->position.". ".$this->remove_nbsp($sect->name));
            }

            $t->setValue('themeTotal#'.$number, $sect->total_hours == 0 ? "-":$sect->total_hours);
            $t->setValue('themeLec#'.$number, $sect->lection_hours == 0 ? "-":$sect->lection_hours);
            $t->setValue('themePr#'.$number, $sect->practice_hours == 0 ? "-":$sect->practice_hours);
            $t->setValue('themeSr#'.$number, $sect->self_hours == 0 ? "-":$sect->self_hours);
            $t->setValue('themeAtt#'.$number, $sect->attestation_hours == 0 ? "-":$sect->attestation_hours);

            $t->setValue('themeKn#'.$number, "");
            $t->setValue('themeAb#'.$number, "");
            $t->setValue('themeSk#'.$number, "");

            $total[0] += $sect->total_hours;
            $total[1] += $sect->lection_hours;
            $total[2] += $sect->practice_hours;
            $total[3] += $sect->lab_hours;
            $total[4] += $sect->self_hours;
            $total[5] += $sect->attestation_hours;
            $number++;
            //темы
            foreach ($sect->themes as $theme)
            {
                $t->setValue('themeName#'.$number, $sect->position.".".$theme->position." ".$this->remove_nbsp($theme->name));
                $t->setValue('themeTotal#'.$number, $theme->total_hours == 0 ? "-":$theme->total_hours);
                $t->setValue('themeLec#'.$number, $theme->lection_hours == 0 ? "-":$theme->lection_hours);
                $t->setValue('themePr#'.$number, $theme->practice_hours == 0 ? "-":$theme->practice_hours);
                $t->setValue('themeSr#'.$number, $theme->self_hours == 0 ? "-":$theme->self_hours);
                $t->setValue('themeAtt#'.$number, $theme->attestation_hours == 0 ? "-":$theme->attestation_hours);


                $kn_text = "Знания: ";
                $knowledges = $theme->knowledges->pluck('name')->implode('; ');
                $kn_text .= $knowledges.".";
                $kn_text = $this->remove_nbsp($kn_text);
                $t->setValue('themeKn#'.$number, $kn_text);

                $skills = $theme->skills;
                if (count($skills) > 0)
                {
                    $sk_text = "Навыки: ";
                    $skills = $skills->pluck('name')->implode('; ');
                    $sk_text .= $skills.".";
                    $t->setValue('themeSk#'.$number, $sk_text);
                }else{
                    $t->setValue('themeSk#'.$number, "");
                }

                $abilities = $theme->abilities;
                if (count($abilities) > 0)
                {
                    $ab_text = "Умения: ";
                    $abilities = $abilities->pluck('name')->implode('; ');
                    $ab_text .= $abilities.".";
                    $t->setValue('themeAb#'.$number, $ab_text);
                }else{
                    $t->setValue('themeAb#'.$number, "");
                }
                $number++;
            }
        }
        $t->setValue('lpTotal', $total[0]);
        $t->setValue('lpLec', $total[1]);
        $t->setValue('lpPr', $total[2]);
        $t->setValue('lpSr', $total[4]);
        $t->setValue('lpAtt', $total[5]);


        //КУП ОЧ
        $t->setComplexBlock('block_kyp_och', $this->get_kyp_och($dpp));

        //КУП ЗАОЧ
        $t->setComplexBlock('block_kyp_zaoch', $this->get_kyp_zaoch($dpp));

        //ЛЕКЦИИ
        // $lec_count = StructureSection::where('st_version_id','=', $sv->id)->where('parent_id','<>', null)->where('lection_hours','>', 0)->get()->count();
        // $t->cloneRow('lectionNum', $lec_count);
        // $number = 1;
        // foreach ($sections as $sect)
        // {
        //     foreach ($sect->themes as $theme)
        //     {
        //         if ($theme->lection_hours > 0)
        //         {
        //             $t->setValue('lectionNum#'.$number, $sect->position.".".$theme->position);
        //             $t->setValue('lectionName#'.$number, $theme->name);
        //             $t->setValue('lectionHours#'.$number, $theme->lection_hours);

        //             $number++;
        //         }
        //     }
        // }

        //ПРАКТИКИ
        // $pr_count = StructureSection::where('st_version_id','=', $sv->id)->where('parent_id','<>', null)->where('practice_hours','>', 0)->get()->count();
        // $t->cloneRow('practiceNum', $pr_count);
        // $number = 1;
        // foreach ($sections as $sect)
        // {
        //     foreach ($sect->themes as $theme)
        //     {
        //         if ($theme->practice_hours > 0)
        //         {
        //             $t->setValue('practiceNum#'.$number, $sect->position.".".$theme->position);
        //             $t->setValue('practiceName#'.$number, $theme->name);
        //             $t->setValue('practiceHours#'.$number, $theme->practice_hours);

        //             $skills = $theme->skills;
        //             if (count($skills) > 0)
        //             {
        //                 $sk_text = "Навыки: ";
        //                 $skills = $skills->pluck('name')->implode('; ');
        //                 $sk_text .= $skills.".";
        //                 $t->setValue('practiceSk#'.$number, $sk_text);
        //             }else{
        //                 $t->setValue('practiceSk#'.$number, "");
        //             }

        //             $abilities = $theme->abilities;
        //             if (count($abilities) > 0)
        //             {
        //                 $ab_text = "Умения: ";
        //                 $abilities = $abilities->pluck('name')->implode('; ');
        //                 $ab_text .= $abilities.".";
        //                 $t->setValue('practiceAb#'.$number, $ab_text);
        //             }else{
        //                 $t->setValue('practiceAb#'.$number, "");
        //             }

        //             $number++;
        //         }
        //     }
        // }

        //

        $sections = StructureSection::where('parent_id','=', null)
         ->where('st_version_id','=', $sv->id)
         ->where('name', '<>', 'Итоговая аттестация')
         ->with(['themes' => function ($query) {$query->orderBy('position');}])
         ->orderBy('position')
         ->get();

        $t->cloneBlock('block_rp', $sections->count(), true, true);
        $idx = 1;
        foreach ($sections as $section)
        {
            $t->setValue('rp_section#'.$idx,$section->name);


            $themes = $section->themes->pluck('name')->toArray();
            //dd($themes);
            $text = implode(". ",$themes).".";
            $text = $this->remove_nbsp($text);
            $t->setValue('rp_themes#'.$idx,$text);
            $idx++;
        }
        //ПРЕПОДЫ
        $comp_count = $dpp->competences()->count();
        if ($comp_count < 2)
        {
            $lectorExp = 'Требования к опыту практической работы: опыт работы в области профессиональной деятельности, связанной с применением работником компетенции, подлежащей совершенствованию и (или) получению в результате освоения Программы (не менее 3 лет).';
        }else{
            $lectorExp = 'Требования к опыту практической работы: опыт работы в области профессиональной деятельности, связанной с применением работником компетенций, подлежащих совершенствованию и (или) получению в результате освоения Программы (не менее 3 лет).';
        }
        $t->setValue('lectorExp', $lectorExp);

        //МТО
        $t->setComplexBlock('block_mto_table', $this->get_mto_table($dpp));

        //НСИ
        $t->setComplexBlock('block_npa_table', $this->get_nsi_table($dpp));

        //ЗАЧЕТ/ЭКЗАМЕН
        $t->setValue('attest', $dpp->att_type);
        //% ТЕСТОВ
        $t->setValue('testPercent', $om->test_percent);
        $t->setValue('testQuestions', $om->test_questions);
        //КОЛ-ВО ПРАКТИЧЕСКИХ
        $tasks = $om->tasks()->count();
        $req_tasks = $om->tasks()->where('required',true)->count() + $om->optional_tasks;
        $t->setValue('reqPrCount', $req_tasks);
        $t->setValue('prCount', $tasks);
        if ($req_tasks == 1) {$prText='практическое задание';}
        else if ($req_tasks < 5) {$prText='практических задания';}
        else {$prText = 'практических заданий';}
        $t->setValue('prText', $prText);

        if ($dpp->has_eok == false) {
            $t->cloneBlock('block_eok', 0, true, true);
        }else{
            $t->cloneBlock('block_eok', 1, true, true);
        }

        //SAVE AND EXPORT
        $pathToSave = storage_path('ПрДПП_'.$dpp->abbreveation.'_ Общая характеристика.docx');
        $t->saveAs($pathToSave);
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
        $toc->addTableContents(array('autoUpdate'=>true),$legend);
        $t->replaceVariableByWordFragment(array('TOC' => $toc), array('type' => 'block'));
        $t->createDocx($pathToSave);
        return response()->download($pathToSave);
    }

    public function printRegalies(Designer $designer)
    {
        if ($designer->title=="Нет звания" && $designer->degree== "Нет степени") {return "";}
        else if ($designer->title=="Нет звания") { return $designer->degree;}
        else if ($designer->degree== "Нет степени") { return $designer->title;}
        else { return $designer->title.", ".$designer->degree;}
    }

    public function printFullname(Designer $designer)
    {
        return $designer->lastname." ".$designer->firstname." ".$designer->middlename;
    }



    public function get_mto_table(Dpp $dpp)
    {
        $tableStyle = array( 'borderColor' => '000000', 'borderSize'  => 1, 'cellMarginLeft' => 107.716535433,'cellMarginRight' => 107.716535433, 'cellMarginTop'  => 0,'cellMarginBottom'  => 0,'width'=> '100%');
        $tableBoldFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 12, 'bold' => true);
        $cellHCenteredNoSpace = array('align' => 'center','spaceAfter' => 0);
        $tableNormalFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 12, 'bold' => false);
        $cellNoSpace = array('spaceAfter' => 0,'lineHeight' => 1);
        $cellNoSpaceJustify = array('spaceAfter' => 0, 'lineHeight' => 1, 'align' => 'both');
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
                $name = $mto->name;
                $name = mb_strtolower(mb_substr($name, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($name, 1, mb_strlen($name), 'UTF-8');
                $table->addRow();
                $table->addCell()->addText($f_n.".".$s_n." ".$name,$tableNormalFont,$cellNoSpace);
                $table->addCell()->addText( $mto->quantity,$tableNormalFont,$cellHCenteredNoSpace);
                $table->addCell()->addText( $mto->measure,$tableNormalFont,$cellHCenteredNoSpace);
                $table->addCell()->addText( $mto->note,$tableNormalFont,$cellNoSpaceJustify);
           }

           foreach ($children_types as $children_type)
           {
                $this_mtos = Mto::where('type_id','=',$children_type->id)->where('dpp_id','=',$dpp->id)->orderBy('position')->get();
                if ($this_mtos->count() != 0)
                {
                    $s_n++;
                    $table->addRow();
                    $table->addCell(null,['gridSpan' => 4])->addText($f_n.".".$s_n." ".$children_type->name,$tableNormalFont,$cellNoSpaceJustify);

                }
                $t_n = 0;
                foreach ($this_mtos as $mto)
                {
                    $t_n++;
                    $name = $mto->name;
                    $name = mb_strtolower(mb_substr($name, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($name, 1, mb_strlen($name), 'UTF-8');
                    $table->addRow();
                    $table->addCell()->addText($f_n.".".$s_n.".".$t_n." ".$name,$tableNormalFont,$cellNoSpace);
                    $table->addCell()->addText( $mto->quantity,$tableNormalFont,$cellHCenteredNoSpace);
                    $table->addCell()->addText( $mto->measure,$tableNormalFont,$cellHCenteredNoSpace);
                    $table->addCell()->addText( $mto->note,$tableNormalFont,$cellNoSpaceJustify);
                }
           }
        }

        return $table;
    }

    public function get_nsi_table(Dpp $dpp)
    {
        $tableStyle = array( 'borderColor' => '000000', 'borderSize'  => 1, 'cellMarginLeft' => 107.716535433,'cellMarginRight' => 107.716535433, 'cellMarginTop'  => 0,'cellMarginBottom'  => 0,'width'=> '100%');
        $tableBoldFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 12, 'bold' => true);
        $cellHCenteredNoSpace = array('align' => 'center','spaceAfter' => 0);
        $tableNormalFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 12, 'bold' => false);
        $cellNoSpace = array('spaceAfter' => 0,'lineHeight' => 1);
        $cellNoSpaceJustify = array('spaceAfter' => 0, 'lineHeight' => 1, 'align' => 'both');
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

    public function get_kyp_och(Dpp $dpp)
    {
        $day_hours = 8;
        $o_days = ceil($dpp->total_hours / $day_hours);
        $mask = 'Д';
        if ($o_days > 10)
        {
            $day_hours = 40;
            $o_days = ceil($dpp->total_hours / $day_hours);
            $mask = 'Н';
        }
        $o_width = 4500 / $o_days;
        $sv = $dpp->st_version;

        $tableStyle = array( 'borderColor' => '000000', 'borderSize'  => 1, 'cellMarginLeft' => 107.716535433,'cellMarginRight' => 107.716535433, 'cellMarginTop'  => 0,'cellMarginBottom'  => 0,'width'=> '100%');
        $tableBoldFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 12, 'bold' => true);
        $cellHCenteredNoSpace = array('align' => 'center','spaceAfter' => 0);
        $tableNormalFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 12, 'bold' => false);
        $cellNoSpace = array('spaceAfter' => 0,'lineHeight' => 1);
        $cellHCentered = array('align' => 'center');
        $cellRowSpan = array('vMerge' => 'restart');
        $cellRowContinue = array('vMerge' => 'continue');
        $cellAllCentered = array('align' => 'center' ,'spaceAfter' => 0);

        $valignmiddle = array('valign' => 'center');

        $table = new \PhpOffice\PhpWord\Element\Table($tableStyle);

         $table->addRow(null,array('tblHeader' => true));
         $table->addCell(null,$cellRowSpan)->addText("Наименование разделов",$tableBoldFont,$cellHCentered);
         $table->addCell(null,['gridSpan' => $o_days])->addText("Количество академических часов по дням",$tableBoldFont,$cellHCenteredNoSpace);
         $table->addCell(1150,$cellRowSpan)->addText("ИТОГО",$tableBoldFont,$cellHCentered);
         $table->addRow(null,array('tblHeader' => true));
         $table->addCell(null, $cellRowContinue); // пустая
         for ($i = 1; $i <= $o_days; $i++)
         {
            $table->addCell($o_width)->addText($mask.$i, $tableBoldFont,$cellAllCentered);
         }
         $table->addCell(null, $cellRowContinue); // пустая

         $sections = StructureSection::where('parent_id','=', null)->where('st_version_id','=', $sv->id)->orderBy('position')->get();
         $busy_cells = 0;
         $free_hours = $day_hours;
         $days_time = [0];
         foreach ($sections as $sect)
         {
             $table->addRow(null);
             if ($sect->name == 'Итоговая аттестация')
             {
                 if ($dpp->att_type == 'Зачет') {$att_text = ' в форме зачета';}
                 if ($dpp->att_type == 'Экзамен') {$att_text = ' в форме экзамена';}
             }else{
                 $att_text = '';
             }
            $table->addCell(null)->addText($sect->position." ".$sect->name.$att_text,$tableNormalFont,$cellNoSpace);
            for ($i = 0; $i < $busy_cells; $i++)
            {
                $table->addCell($o_width);
            }
            if ($sect->total_hours < $free_hours)
            {
                $table->addCell($o_width,$valignmiddle)->addText($sect->total_hours,$tableNormalFont,$cellHCenteredNoSpace);
                $free_hours -= $sect->total_hours;
                $skip_cells = $o_days - $busy_cells-1;
                $days_time[$busy_cells]+= $sect->total_hours;
            }
            else if ($sect->total_hours == $free_hours)
            {
                $table->addCell($o_width,$valignmiddle)->addText($sect->total_hours,$tableNormalFont,$cellHCenteredNoSpace);
                $days_time[$busy_cells]+= $sect->total_hours;
                $days_time[$busy_cells+1] = 0;
                $busy_cells++;
                $free_hours = $day_hours;
                $skip_cells = $o_days - $busy_cells;
            }
            else if ($sect->total_hours > $free_hours)
            {
                $to_serve = $sect->total_hours;
                $skip_cells = $o_days - $busy_cells - 1;
                while ($to_serve > 0)
                {
                    if ($to_serve >= $free_hours)
                    {
                        $table->addCell($o_width,$valignmiddle)->addText(round($free_hours,2),$tableNormalFont,$cellHCenteredNoSpace);
                        if ($to_serve > $free_hours) {
                              $skip_cells--;
                        }
                        $to_serve -= $free_hours;
                        $days_time[$busy_cells]+= $free_hours;
                        $days_time[$busy_cells+1] = 0;
                        $busy_cells++;
                        $free_hours = $day_hours;
                    }else{
                        $cell = $table->addCell($o_width, $valignmiddle);
                        $to_serve > 0.001 ? $cell->addText(round($to_serve,99),$tableNormalFont,$cellHCenteredNoSpace):"";
                        $free_hours = round($free_hours - $to_serve,2);
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
            $cell = $table->addCell($o_width, $valignmiddle);
            $sect->total_hours > 0 ? $cell->addText($sect->total_hours,$tableBoldFont,$cellHCenteredNoSpace):"";
         }
         $table->addRow(null);
         $table->addCell()->addText('Всего ак. часов',$tableBoldFont,$cellHCenteredNoSpace);
         if ($days_time[count($days_time)-1] == 0) {array_pop($days_time);}
         foreach ($days_time as $key=>$value)
         {
            $table->addCell($o_width)->addText($value,$tableBoldFont,$cellHCenteredNoSpace);
         }
         $table->addCell()->addText(array_sum($days_time),$tableBoldFont,$cellHCenteredNoSpace);
         return  $table;
    }

    public function get_kyp_zaoch(Dpp $dpp)
    {
        $day_hours = 4;
        $z_days = ceil($dpp->total_hours / $day_hours);
        $mask = 'Д';
        if ($dpp->total_hours > 36)
        {
            $day_hours = 20;
            $z_days = ceil($dpp->total_hours / $day_hours);
            $mask = 'Н';
        }
        $z_width = 4500 / $z_days;
        $sv = $dpp->st_version;
        $tableStyle = array( 'borderColor' => '000000', 'borderSize'  => 1, 'cellMarginLeft' => 107.716535433,'cellMarginRight' => 107.716535433, 'cellMarginTop'  => 0,'cellMarginBottom'  => 0,'width'=> '100%');
        $tableBoldFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 12, 'bold' => true);
        $cellHCenteredNoSpace = array('align' => 'center', 'valign' => 'center','spaceAfter' => 0,'valign' => 'center');
        $tableNormalFont = array('name' => 'Times New Roman','color' => '000000', 'size' => 12, 'bold' => false);
        $cellNoSpace = array('spaceAfter' => 0,'lineHeight' => 1);
        $cellHCentered = array('align' => 'center','valign' => 'center');
        $cellRowSpan = array('vMerge' => 'restart', 'valign' => 'center');
        $cellRowContinue = array('vMerge' => 'continue');
        $cellAllCentered = array('align' => 'center', 'valign' => 'center','spaceAfter' => 0);
        $valignmiddle = array('valign' => 'center');


        $table = new \PhpOffice\PhpWord\Element\Table($tableStyle);
        $table->addRow(null,array('tblHeader' => true));
         $table->addCell(null,$cellRowSpan)->addText("Наименование модулей",$tableBoldFont,$cellHCentered);
         $table->addCell(4500,['gridSpan' => $z_days])->addText("Количество академических часов по дням",$tableBoldFont,$cellHCenteredNoSpace);
         $table->addCell(1150,$cellRowSpan)->addText("ИТОГО",$tableBoldFont,$cellHCentered);
         $table->addRow(null,array('tblHeader' => true));
         $table->addCell(null, $cellRowContinue); // пустая
         for ($i = 1; $i <= $z_days; $i++)
         {
            $table->addCell($z_width, $valignmiddle)->addText($mask.$i, $tableBoldFont,$cellAllCentered);
         }
         $table->addCell(null, $cellRowContinue); // пустая
         $busy_cells = 0;
         $free_hours = $day_hours;
         $days_time = [0];
         $sections = StructureSection::where('parent_id','=', null)->where('st_version_id','=', $sv->id)->orderBy('position')->get();
         foreach ($sections as $sect)
         {
            $table->addRow(null);
             if ($sect->name == 'Итоговая аттестация')
             {
                 if ($dpp->att_type == 'Зачет') {$att_text = ' в форме зачета';}
                 if ($dpp->att_type == 'Экзамен') {$att_text = ' в форме экзамена';}
             }else{
                 $att_text = '';
             }
            $table->addCell(null)->addText($sect->position." ".$sect->name.$att_text,$tableNormalFont,$cellNoSpace);
            for ($i = 0; $i < $busy_cells; $i++)
            {
                $table->addCell($z_width);
            }
            if ($sect->total_hours < $free_hours)
            {
                $table->addCell($z_width, $valignmiddle)->addText($sect->total_hours,$tableNormalFont,$cellHCenteredNoSpace);
                $free_hours -= $sect->total_hours;
                $skip_cells = $z_days - $busy_cells-1;
                $days_time[$busy_cells]+= $sect->total_hours;
            }
            else if ($sect->total_hours == $free_hours)
            {
                $table->addCell($z_width, $valignmiddle)->addText($sect->total_hours,$tableNormalFont,$cellHCenteredNoSpace);
                $days_time[$busy_cells]+= $sect->total_hours;
                $days_time[$busy_cells+1] = 0;
                $busy_cells++;
                $free_hours = $day_hours;
                $skip_cells = $z_days - $busy_cells;
            }
            else if ($sect->total_hours > $free_hours)
            {
                $skip_cells = $z_days - $busy_cells-1;
                $to_serve = $sect->total_hours;
                while ($to_serve > 0)
                {
                    if ($to_serve >= $free_hours)
                    {
                        $table->addCell($z_width, $valignmiddle)->addText($free_hours,$tableNormalFont,$cellHCenteredNoSpace);
                        if ($to_serve > $free_hours) {$skip_cells--;}
                        $to_serve -= $free_hours;
                        $days_time[$busy_cells]+= $free_hours;
                        $days_time[$busy_cells+1] = 0;
                        $busy_cells++;
                        $free_hours = $day_hours;
                    }else{
                        $table->addCell($z_width, $valignmiddle)->addText($to_serve,$tableNormalFont,$cellHCenteredNoSpace);
                        $free_hours = round($free_hours - $to_serve,2);
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
            $table->addCell($z_width, $valignmiddle)->addText($sect->total_hours,$tableBoldFont,$cellHCenteredNoSpace);
         }
         $table->addRow(null);
         $table->addCell()->addText('Всего ак. часов',$tableBoldFont,$cellHCenteredNoSpace);
         if ($days_time[count($days_time)-1] == 0) {array_pop($days_time);}
         foreach ($days_time as $key=>$value)
         {
            $table->addCell($z_width)->addText($value,$tableBoldFont,$cellHCenteredNoSpace);
         }
         $table->addCell()->addText(array_sum($days_time),$tableBoldFont,$cellHCenteredNoSpace);
        return  $table;
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

    public function formatDays($days) {
        $lastDigit = $days % 10;
        $lastTwoDigits = $days % 100;

        if ($lastTwoDigits >= 11 && $lastTwoDigits <= 19) {
            $suffix = 'дней';
        } else {
            switch ($lastDigit) {
                case 1:
                    $suffix = 'день';
                    break;
                case 2:
                case 3:
                case 4:
                    $suffix = 'дня';
                    break;
                default:
                    $suffix = 'дней';
                    break;
            }
        }

        return $suffix;
    }

    public function formatHours($hours) {
        $lastDigit = $hours % 10;
        $lastTwoDigits = $hours % 100;

        if ($lastTwoDigits >= 11 && $lastTwoDigits <= 19) {
            $suffix = 'часов';
        } else {
            switch ($lastDigit) {
                case 1:
                    $suffix = 'час';
                    break;
                case 2:
                case 3:
                case 4:
                    $suffix = 'часа';
                    break;
                default:
                    $suffix = 'часов';
                    break;
            }
        }

        return $suffix;
    }

    public function formatAcadem($hours) {
        $lastDigit = $hours % 10;
        $lastTwoDigits = $hours % 100;

        switch ($lastDigit) {
            case 1:
                $suffix = 'академический';
                break;

            default:
                $suffix = 'академических';
                break;
        }

        return $suffix;
    }

    public function export_pp (Dpp $dpp)
    {
        $t = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('/templates/rut_pp_template_char.docx'));
        \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true);

        $iv = $dpp->ish_version;
        $zv = $dpp->zun_version;
        $om = $dpp->om_version;

        $pathToSave = storage_path('ДПП_ПП_'.$dpp->abbreveation.'_ Общая характеристика.docx');
        $t->saveAs($pathToSave);
        $t = new \Phpdocx\Create\CreateDocxFromTemplate($pathToSave);
        $t->createDocx($pathToSave);
        return response()->download($pathToSave);
    }

    public function export_ck (Dpp $dpp)
    {
        $t = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('/templates/rut_pp_ck_template_char.docx'));
        \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true);

        $iv = $dpp->ish_version;
        $zv = $dpp->zun_version;
        $om = $dpp->om_version;

        //УТВЕЖДЕНИЕ
        $t->setValue('signatory_fio', $dpp->signatory_fio ?? "");
        $t->setValue('signatory_job', $dpp->signatory_job ?? "");
        //НАЗВАНИЕ ДПП
        $t->setValue('dppName', $dpp->name);

        //ГОД
        $year = $dpp->year;
        $t->setValue('dppYear', $year);
        $t->setValue('signature_year', $year);

        //АННОТАЦИЯ
        $t->setValue('dppAnnotation', $iv->annotationDescription);

        //ИСПОЛНИТЕЛИ
        $designers = $dpp->designers;
        $t->cloneRow('job', count($designers));
        $index = 1;
        foreach ($designers as $key=>$designer)
        {
            $t->setValue('job#'.$index, $this->printRegalies($designer));
            $t->setValue('short_name#'.$index, $this->printFullname($designer));
            $t->setValue('work#'.$index, $designer->task);
            $index++;
        }

        /*ОСНОВАНИЯ*/
        $pss = $iv->prof_standarts;
        $fgoses = $iv->fgoses;

        $ekses = $iv->ekses;
        $ektses = $iv->ektses;

        //ФГОС
        if (count($fgoses) == 0) {
            $t->cloneBlock('fgos_block', 0, true, true);
            $fgos_main_row = "<нет ФГОС>";
        }else{
            $main_fgos = $fgoses[0];
            $fgos_main_row = $main_fgos->code. " ".$main_fgos->name;

            $t->cloneBlock('fgos_block', count($fgoses), true, true);
            foreach ($fgoses as $index => $fgos) {
                $n = $index+1;
                $t->setValue('fgos_level#'.$n, $fgos->fgos_level_id==1 ? "среднего профессиональньго":"высшего");
                $t->setValue('fgos_code#'.$n, $fgos->code);
                $t->setValue('fgos_name#'.$n, $fgos->name);
                $t->setValue('fgos_regalia#'.$n, $fgos->requisites);
            }
        }
        $t->setValue('main_fgos', $fgos_main_row);

        //ПРОФСТАНДАРТ
        if (count($pss) == 0) {
            $t->cloneBlock('ps_block', 0, true, true);
        }else{

            $t->cloneBlock('ps_block', count($pss), true, true);
            foreach ($pss as $index => $ps) {
                $n = $index+1;
                $t->setValue('ps#'.$n, $ps->fullName);
            }
        }

        //ЕКС
        if (count($ekses) == 0) {
            $t->cloneBlock('eks_block', 0, true, true);
        }else{

            $t->cloneBlock('eks_block', count($ekses), true, true);
            foreach ($ekses as $index => $eks) {
                $n = $index+1;
                $t->setValue('eks_name#'.$n, $eks->nameProfession);
            }
        }

        //ОБЛАСТЬ ПРОФЕССИОНАЛЬНОЙ ДЕЯТЕЛЬНОСТИ
        $t->setValue('professional_field', $iv->professional_field->text ?? "не заполнено");

        // ОБЪЕКТЫ ПРОФЕССИОНАЛЬНОЙ ДЕЯТЕЛЬНОСТИ
        $professional_objects = $iv->professional_objects;
        $t->cloneBlock('object_block', count($professional_objects), true, true);
        foreach ($professional_objects as $index => $object) {
            $n = $index+1;
            $t->setValue('object#'.$n, $object->text);
        }

        //ТРЕБЛОВАНИЯ и КВАЛИФИКАЦИЯ
        $qual = $iv->qualification;
        if (strlen($qual) > 0) {
            $t->cloneBlock('has_qual_block', 1, true, true);
            $t->cloneBlock('no_qual_block', 0, true, true);
        }else{
            $t->cloneBlock('has_qual_block', 0, true, true);
            $t->cloneBlock('no_qual_block', 1, true, true);
        }
        $t->setValue('sphere#1', $iv->professional_sphere);
        $t->setValue('sphere', $iv->professional_sphere);

        $t->setValue('new_qual', $iv->qualification);
        //IT-неIT
        if ($iv->direction_id == 1) {
            $t->cloneBlock('no_it_block', 1, true, true);
            $t->cloneBlock('it_block', 0, true, true);

            $t->setValue('new_qual#1', $iv->qualification);
            $t->setValue('is_it','не отнесенной');
        }else{
            $t->cloneBlock('no_it_block', 0, true, true);
            $t->cloneBlock('it_block', 1, true, true);
            $t->setValue('sphere_rp#1',$iv->digital_sphere->name_rp);
            $t->setValue('new_qual#1', $iv->qualification);
            $t->setValue('is_it','отнесенной');
        }


        //ФОРМА
        $form = $iv->edu_form;
        $form_rp = '<не выбрано>';
        if ($form=='Очная') {$form_rp='очной';}
        if ($form=='Очно-заочная') {$form_rp='очно-заочной';}
        if ($form=='Заочная') {$form_rp='заочной';}
        $t->setValue('form', $form_rp);

        //С ДОТОМ/БЕЗ
        if ($iv->edu_form_dot == true) {
            $with_dot = 'с применением дистанционных образовательных технологий, электронного обучения';
        }else{
            $with_dot = '';
        }
        $t->setValue('with_dot', $with_dot);
        //ЧАСЫ
        $t->setValue('hours', $dpp->total_hours);

        //ПЕРИОД
        $period_name = $iv->edu_period_name;
        if ($period_name=='Календарные дни') {$period_name='дней';}
        if ($period_name=='Недели') {$period_name='недель';}
        if ($period_name=='Месяцы') {$period_name='месяцев';}
        $t->setValue('period_duration', $iv->edu_period_duration);
        $t->setValue('period_name', $period_name);


        //ЗАДАЧИ
        if (count($zv->skills) == 0) {
            $zuns = 'знаний и умений';
        }else{
            $zuns = 'знаний, умений и навыков';
        }
        $t->setValue('zuns', $zuns);

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

        //УЧЕБНЫЙ ПЛАН
        $sv = StructureVersion::find($dpp->st_version_id);
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

        //КАЛЕНДАРНЫЙ УЧЕБНЫЙ ГРАФИК
        $sec_number = 0;
        $sections = StructureSection::where('parent_id', '=', null)
            ->where('st_version_id', '=', $sv->id)
            ->where('parent_id', '=', null)
            ->orderBy('position')
            ->get();

        $t->cloneRow('kp_module_name', $sections->count(), true, true);
        $ttotal = 0;
        foreach ($sections as $section)
        {
            $sec_number++;
            $t->setValue('mh#'.$sec_number, $sec_number);
            $t->setValue('kp_module_name#'.$sec_number, $section->name);
            $t->setValue('kp_module_hours#'.$sec_number, $section->total_hours);
            $ttotal += $section->total_hours;
        }
        $t->setValue('kp_total_hours',$ttotal);


        //РАБОЧИЕ ПРОГРАММЫ
        $sections = StructureSection::where('parent_id', '=', null)
            ->where('st_version_id', '=', $sv->id)
            ->where('name', '<>', 'Итоговая аттестация')
            ->has('themes')
            ->with(['themes' => function ($query) {
                $query->orderBy('position');
            }])
            ->orderBy('position')
            ->get();

        $t->cloneBlock('block_rp', $sections->count(), true, true);
        $idx = 1;
        foreach ($sections as $section)
        {
            $t->setValue('rp_section#'.$idx,$section->name);


            $themes = $section->themes->pluck('name')->toArray();
            //dd($themes);
            $text = implode(". ",$themes).".";
            $text = $this->remove_nbsp($text);
            $t->setValue('rp_themes#'.$idx,$text);
            $idx++;
        }

        //ФОРМЫ АТТЕСТАЦИИ
        $z_modules = $sv->get_sections
            ->where('attestation_form','Зачет')
            ->where('name','<>','Производственная практика');
        $z_names = $z_modules->pluck('name')->implode(', ');
        if (count($z_modules)>0) {
            $t->cloneBlock('z_block', 1, true, true);
            $t->setValue('z_modules#1',$z_names);
        }else{
            $t->cloneBlock('z_block', 0, true, true);
        }


        $zo_modules = $sv->get_sections
            ->where('attestation_form','Зачет с оценкой')
            ->where('name','<>','Производственная практика');
        $zo_names = $zo_modules->pluck('name')->implode(', ');
        if (count($zo_modules)>0) {
            $t->cloneBlock('zo_block', 1, true, true);
            $t->setValue('zo_modules#1',$zo_names);
        }else{
            $t->cloneBlock('zo_block', 0, true, true);
        }
        $t->setValue('zo_modules',$zo_names);

        $pp_section = $sv->get_sections->where('name','Производственная практика')->first();
        if ($pp_section) {
            $pp_form = $pp_section->attestation_form;
        }else{
            $pp_form = "Зачет с оценкой";
        }
        $t->setValue('pp_form', $pp_form);

        //МТО
        $t->setComplexBlock('block_mto_table', $this->get_mto_table($dpp));

        //НСИ
        $t->setComplexBlock('block_npa_table', $this->get_nsi_table($dpp));

        /*SAVE*/
        $pathToSave = storage_path('ДПП_ПП_'.$dpp->abbreveation.'_ Общая характеристика.docx');
        $t->saveAs($pathToSave);
        $t = new \Phpdocx\Create\CreateDocxFromTemplate($pathToSave);
        $t->createDocx($pathToSave);
        $t->setTemplateSymbol('${', '}');
        // TOC
        $toc = new \Phpdocx\Elements\WordFragment($t);
        $legend = array(
            'text' => 'Щелкните здесь, чтобы обновить содержание',
            'color' => '000000',
            'bold' => false,
            'fontSize' => 14,
        );
        $toc->addTableContents(array('autoUpdate'=>true),$legend);
        $t->replaceVariableByWordFragment(array('TOC' => $toc), array('type' => 'block'));
        $t->createDocx($pathToSave);

        return response()->download($pathToSave);
    }
}
