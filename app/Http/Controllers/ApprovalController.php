<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Dpp;
use App\IshVersion;
use App\StructureSection;
use App\Knowledge;

class ApprovalController extends Controller
{
    public function show(Dpp $dpp)
    {
        $result = [];
        //$result["ishVersion"] = IshVersion::find($dpp->ish_version_id);
        $result["stages"] = $this->get_errors($dpp);

        $themes = StructureSection::where('parent_id','<>',null)
        ->where('st_version_id',$dpp->st_version_id)->get();
        $lecH = 0; $prH = 0; $labH = 0;
        foreach ($themes as $theme)
        {
            $lecH += $theme->lection_hours;
            $prH += $theme->practice_hours;
            $labH += $theme->lab_hours;
        }

        $result["hours"] = [];
        $result["hours"]["lec"] = $lecH;
        $result["hours"]["pr"] = $prH;
        $result["hours"]["lab"] = $labH;
        return json_encode($result);
    }

    public function get_errors(Dpp $dpp)
    {
        $result = [];
        /* 1 Этап */
        $stage = [];
        $stage["name"] = "Ввод исходных данных";
        $stage["errors"] = []; $stage["warnings"] = [];
        $iv = $dpp->ish_version;
        //нпо
        $npos = $dpp->ish_version()->withCount('ektses')->withCount('ekses')->withCount('world_skills')
        ->withCount('prof_standarts')->withCount('corporate_requirements')->withCount('fgoses')->get()->first();
        if (($npos->ektses_count + $npos->ekses_count + $npos->world_skills_count + $npos->prof_standarts_count + $npos->corporate_requirements_count + $npos->fgoses_count) == 0 )
        {
            $stage["errors"][] = "Не выбраны нормативные правовые основания разработки";
        }

        //образование
        if ($iv->prof_levels->count() == 0)
        {
            $stage["errors"][] = "Не выбраны требования к уровню профессионального образования";
        }
        //квалификация
        if ($iv->req_user_kval == null)
        {
            $stage["errors"][] = "Не введены требования к квалификации";
        }
        //аннотация
        if ($iv->annotationDescription == null)
        {
            $stage["errors"][] = "Не введена аннотация программы";
        }
        if ((is_null($iv->professional_sphere) || strlen(trim($iv->professional_sphere)) == 0) && $dpp->dpp_type_id == 2)
        {
            $stage["errors"][] = "Не заполнена Сфера профессиональной деятельности";
        }
        //нси
        $nsis = $dpp->ish_version->nsis()->withCount('knowledges')->withCount('abilities')->withCount('skills')->get();
        foreach ($nsis as $nsi)
        {
            if (($nsi->knowledges_count + $nsi->abilities_count + $nsi->skills_count) == 0 )
            {
                $stage["warnings"][] = "НСИ «".$nsi->nsiFullName."» не сопоставлено ни с одним элементом конмпетентностного профиля";
            }
        }
        //часы
        if ($dpp->total_hours < 16)
        {
            $stage["errors"][] = "Трудоемкость освоения не может быть меньше 16 часов";
        }
        $structure_hours = $dpp->st_version->parent_sections()->sum('total_hours');
        $tolerance = 0.001;
        if (abs($dpp->total_hours - $structure_hours) > $tolerance)
        {
            $stage["errors"][] = "Введенная трудоемкость освоения (".$dpp->total_hours." ч.) не соответствует рассчитанной в структуре ДПП (".$structure_hours." ч.)";
        }
        array_push($result,$stage);

        /* 2 Этап */
        $stage = [];
        $stage["name"] = "Проектирование компетентностного профиля";
        $stage["errors"] = []; $stage["warnings"] = [];

        $bad_skills = $dpp->skills()->where('valid',0)->get(); foreach($bad_skills as $skill) { $stage["errors"][] = "Не до конца заполнен навык «".$skill->name."»";}
        $bad_abilities = $dpp->abilities()->where('valid',0)->get(); foreach($bad_abilities as $ability) { $stage["errors"][] = "Не до конца заполнено умение «".$ability->name."»";}
        $bad_knowledges = $dpp->knowledges()->where('valid',0)->get(); foreach($bad_knowledges as $knowledge) { $stage["errors"][] = "Не до конца заполнено знание «".$knowledge->name."»";}
        $competences = $dpp->competences;
        if ($competences->count() == 0) { $stage["errors"][] = "Не сформировано ни одной компетенции"; }
        array_push($result,$stage);

        /* 3 Этап */
        $stage = [];
        $stage["name"] = "Проектирование оценочных материалов";
        $stage["errors"] = []; $stage["warnings"] = [];
        $knowledges = $dpp->knowledges()->withCount('questions')->get();
        //вопросы
        foreach ($knowledges as $knowledge)
        {
           if ($knowledge->questions_count < 3) { $stage["warnings"][] = "Для знания «".$knowledge->name."» количество вопросов меньше 3"; }
        }

        //навыки без заданий
        $free_skills = $dpp->skills()->doesntHave('task_subjects')->get();
        foreach ($free_skills as $skill) {
            $stage["errors"][] = "Отсутствует задания для проверки навыка «".$skill->name."»";
        }

        //умения без заданий
        $free_abilities = $dpp->abilities()->doesntHave('task_subjects')->get();
        foreach ($free_abilities as $ability) {
            $stage["errors"][] = "Отсутствует задания для проверки умения «".$ability->name."»";
        }

        //задания
        $tasks = $dpp->tasks;
        foreach ($tasks as $task)
        {
           $subjects = $task->subjects;
           if ($subjects->count() == 0) {$stage["errors"][] = "«".$task->name." ".$task->position."» - отсутствует предмет оценки";}
           else{
               foreach ($subjects as $subject)
               {
                   $objects = $subject->objects;
                   if ($objects->count() == 0 && $task->type_id == 1) {$stage["errors"][] = " «".$task->name." ".$task->position."» - у предмета оценки «".$subject->name."» отсутствует объект оценки.";}
               }
           }

           if ($task->tyoe_id == 3 && $task->steps->count() == 0) {$stage["errors"][] = " «".$task->name." ".$task->position."» - у отсутствуют шаги.";}
        }
        array_push($result,$stage);

        /* 4 Этап */
        $stage = [];
        $stage["name"] = "Проектирование структуры ДПП";
        $stage["errors"] = []; $stage["warnings"] = [];
        $st = $dpp->st_version;
        $sections = $st->parent_sections()->get();
        foreach ($sections as $section)
        {
            //нет тем
            if ( (count($section->themes)==0) && ($section->name != "Итоговая аттестация") )
            {
                $stage["errors"][] = "В разделе «".$section->position.". ".$section->name."» отсутствуют темы";
            }
            //итоговая атт. нет часов
            if ( ($section->total_hours==0) && ($section->name == "Итоговая аттестация") )
            {
                $stage["errors"][] = "Не указаны часы на итоговую аттестацию";
            }
            foreach ($section->themes as $theme)
            {
                if ($theme->total_hours == 0)
                {
                    $stage["errors"][] = "В теме «".$section->position.".".$theme->position." ".$theme->name."» отсутствуют часы";
                }
            }
        }

        array_push($result,$stage);

         /* 5 Этап */
         $stage = [];
         $stage["name"] = "Проектирование УММ";
         $stage["errors"] = []; $stage["warnings"] = [];
         $sections = StructureSection::with(['themes.contents' => function ($query) {$query->orderBy('position');}])
         ->where('parent_id','=', null)
         ->where('st_version_id','=', $dpp->st_version_id)
         ->orderBy('position')
         ->get();
         foreach ($sections as $section)
         {
            foreach ($section->themes as $theme)
            {
                //лекции
                if ($theme->lection_hours > 0)
                {
                    $lection = $theme->contents->where('type','lec')->where('is_loaded',true);
                    if (count($lection) == 0)
                    {
                        $stage["errors"][] = "Для темы ".$theme->name." не загружен конспект лекций (часы на лекцию ".$theme->lection_hours.")";
                    }
                }
                // практика
                if ($theme->practice_hours > 0)
                {
                    $lection = $theme->contents->where('type','pr')->where('is_loaded',true);
                    if (count($lection) == 0)
                    {
                        $stage["errors"][] = "Для темы «".$theme->name."» не загружены мет. указания. по практике (часы на практику ".$theme->practice_hours.")";
                    }
                }
                // лабораторные
                if ($theme->lab_hours > 0)
                {
                    $lection = $theme->contents->where('type','lab')->where('is_loaded',true);
                    if (count($lection) == 0)
                    {
                        $stage["errors"][] = "Для темы «".$theme->name."» не загружены мет. указания. по лаб.раб. (часы на лабораторные ".$theme->lab_hours.")";
                    }
                }
            }
         }
         array_push($result,$stage);

        return $result;
    }
}
