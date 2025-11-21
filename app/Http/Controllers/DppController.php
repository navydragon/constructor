<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\DppType;
use App\Dpp;
use App\User;
use App\Role;
use App\Competence;
use App\Skill;
use App\Ability;
use App\Knowledge;
use App\StageType;
use App\DppStage;
use App\DppUserRole;
use App\ZunVersion;
use App\IshVersion;
use App\OmVersion;
use App\QuestionType;
use App\Question;
use App\StructureVersion;
use App\StructureSection;
use App\ContentVersion;
use App\Content;
use App\Mto;
use App\Nsi;
use App\Designer;
use App\QualificationRequirement;
use App\ProfessionalObject;
use App\Lection;
use App\LectionPart;
use App\AdditionalFile;
use App\TaskStep;
use App\TaskSubject;
use App\TaskObject;
use App\TaskSpecification;
use App\TaskQuestion;
use App\TaskAdditionalFile;
use App\DppTypologyPart;
use Illuminate\Support\Facades\File;
use App\Http\Requests\DppRequest;
use App\Http\Controllers\ZunVersionController;
use App\Http\Controllers\OmVersionController;
use Auth;

use App\Http\Resources\Dpp as DppResource;
use App\Http\Resources\DppCollection;


class DppController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $company_id = $user->company_id;

        if ($user->is_admin())
        {
            $dpps = Dpp::with(['author', 'participants.user', 'participants.role'])->get();
        }else{
            $dpps = Dpp::with(['author', 'participants.user', 'participants.role'])
                ->whereHas('author', function($query) use ($company_id) {
                    $query->where('company_id', $company_id);
                })->get();
        }
        return new DppCollection($dpps);
    }

    public function show(Dpp $dpp)
    {
        return new DppResource($dpp);
    }

    public function store(DppRequest $request)
    {
        $dpp = new Dpp;
        $dpp->name = $request->input('dpp.name');
        $dpp->total_hours = $request->input('dpp.totalHours');
        $dpp->dpp_type_id = $request->input('dpp.type');
        $dpp->author_id = auth()->user()->id;
        $dpp->abbreveation = $dpp->setAbbreveation($request->input('dpp.name'));
        $dpp->status_id = 1;
        $dpp->is_digital = $request->input('dpp.isDigital') == 1 ? true : false;

        $dpp->year = Date("Y");
        $dpp->save();
        if ( $request->input('dpp.isDigital') == 1) {
            $dpp->ish_version->prof_levels()->sync([1,2]);
        }
        $participants = $request->input('dpp.participants');
        foreach($participants as $item)
        {
            $dur = new DppUserRole;
            $dur->user_id = $item["userId"];
            $dur->dpp_id = $dpp["id"];
            $dur->role_id = $item["roleId"];
            $dur->save();
        }
        return new DppResource($dpp);
    }

    public function update(Dpp $dpp, DppRequest $request)
    {
        $dpp->name = $request->input('dpp.name');
        $dpp->is_archieved = $request->input('dpp.isArchieved') == 1 ? true : false;
        $dpp->total_hours = $request->input('dpp.totalHours');
        $dpp->year = $request->input('dpp.year');
        $dpp->dpp_type_id = $request->input('dpp.type');
        $dpp->is_digital = $request->input('dpp.isDigital') == 1 ? true : false;
        $dpp->abbreveation = $dpp->setAbbreveation($request->input('dpp.name'));
        $dpp->save();
        if ( $request->input('dpp.isDigital') == 1) {
            $dpp->ish_version->prof_levels()->sync([1,2]);
        }
        $dpp->participants()->delete();
        $participants = $request->input('dpp.participants');
        foreach($participants as $item)
        {
            $dur = new DppUserRole;
            $dur->user_id = $item["userId"];
            $dur->dpp_id = $dpp["id"];
            $dur->role_id = $item["roleId"];
            $dur->save();
        }
        return new DppResource($dpp);
    }

    public function destroy(Dpp $dpp)
    {
        $dpp->delete();
        return $dpp->id;
    }


    public function get_all_dpp_types()
    {
        $dpp_types = DppType::all();
        foreach ($dpp_types as $dpp_type)
        {
            $dpp_type->text = $dpp_type->name;
            $dpp_type->value = $dpp_type->id;
        }
        return $dpp_types;
    }

    public function get_all_dpps()
    {
        $dpps = Dpp::all();
        foreach ($dpps as $dpp)
        {
            $dpp->type_name = $dpp->type->name;
            $dpp->participants = $dpp->participants;
            $dpp_sc = Skill::where('dpp_id','=',$dpp->id)->get()->count();
            $dpp_ac = Ability::where('dpp_id','=',$dpp->id)->get()->count();
            $dpp_kc = Knowledge::where('dpp_id','=',$dpp->id)->get()->count();
            $dpp->zuns = $dpp_sc + $dpp_ac + $dpp_kc;
        }
        return $dpps;
    }

    public function store_dpp(Request $request)
    {
        $dpp = new Dpp;
        $dpp->name = $request->name;
        $dpp->total_hours = $request->total_hours;
        $dpp->dpp_type_id = $request->type;
        $dpp->author_id = Auth::user()->id;
        $dpp->save();

        $go = true;
        $st = StageType::where('is_first',true)->get()->first();
        while ($go == true)
        {
            $ds = new DppStage;
            $ds->dpp_id = $dpp->id;
            $ds->stage_type_id = $st->id;
            if ($st->is_first == true){
                $ds->stage_status_id = 1;
            }else{
                $ds->stage_status_id = 2;
            }
            $ds->save();
            if ($st->is_first == true){
                $dpp->current_stage_id = $ds->id;
                $dpp->save();
            }
            if ($st->next_stage == null)
            {
                $go = false;
            }else{
                $st = StageType::find($st->next_stage);
            }
        }
        /*
        $stage_types = StageType::all();
        foreach ($stage_types as $st) {
            $ds = new DppStage;
            $ds->dpp_id = $dpp->id;
            $ds->stage_type_id = $st->id;
            if ($st->id == 1){
                $ds->stage_status_id = 1;

            }else{
                $ds->stage_status_id = 2;
            }
            $ds->save();
            if ($st->id == 1){
                $dpp->current_stage_id = $ds->id;
                $dpp->save();
            }
        }
        */
        $dpp->type_name = $dpp->type->name;
        $dpp->participants = $dpp->participants;
        return $dpp;
    }

    public function get_dpp_to_config(Dpp $dpp)
    {
        $dpp->type_name = $dpp->type->name;
        $dpp->participants = $dpp->participants;

        foreach($dpp->participants as $user)
        {
            $u = User::find($user->user_id);
            $user->fullname = $u->fullname;
            $user->user_id = $u->id;
            $r = Role::find($user->role_id);
            $user->rolename = $r->name;
            $user->role_id = $r->id;
        }

        return $dpp;
    }

    public function get_all_my_dpps()
    {
        $user_id = Auth::user()->id;

    // Получение всех DPPs для пользователя с eager loading
    $my_dpps = DppUserRole::where('user_id', $user_id)
        ->with('dpp.participants.user', 'dpp.participants.role')
        ->distinct()
        ->get()
        ->pluck('dpp') // Выберем только DPPs из коллекции
        ->filter(function($dpp) {
            return !$dpp->is_archieved; // Фильтрация по is_archieved == 0
        });

    $my_dpps = Dpp::distinct()
        ->select('dpps.*')
        ->join('dpp_user_roles', 'dpps.id', '=', 'dpp_user_roles.dpp_id')
        ->where('dpp_user_roles.user_id', '=', $user_id)
        ->where('dpps.is_archieved','=',0)
        ->with('participants.user', 'participants.role')
        ->get();

        return DppResource::collection($my_dpps);


    //     // ->each(function($dpp) use ($user_id) {
    //     //     // Добавляем дополнительную информацию к каждому участнику
    //     //     foreach ($dpp->participants as $participant) {
    //     //         $participant->fullname = $participant->user->fullname;
    //     //         $participant->email = $participant->user->email;
    //     //         $participant->phone = $participant->user->phone;
    //     //         $participant->rolename = $participant->role->name;
    //     //         $participant->is_me = $participant->user_id == $user_id;
    //     //     }
    //     // });

    // return $my_dpps;

        $user_id = Auth::user()->id;
        $my_dpps = DppUserRole::select('dpp_id')->where('user_id',$user_id)->distinct()->get();
        $res = [];
        foreach ($my_dpps as $my_dpp)
        {
            $dpp = Dpp::find($my_dpp->dpp_id);
            if ($dpp->is_archieved == 0)
            {
                $my_dpp->name = $dpp->name;
                $my_dpp->participants = $dpp->participants;
                $my_dpp->is_digital = $dpp->is_digital;
                $my_dpp->type = $dpp->type;
                foreach($my_dpp->participants as $user)
                {
                    $u = User::find($user->user_id);
                    $user->fullname = $u->fullname;
                    $user->user_id = $u->id;
                    $user->email = $u->email;
                    $user->phone = $u->phone;
                    $r = Role::find($user->role_id);
                    $user->rolename = $r->name;
                    $user->role_id = $r->id;
                    if ($user->user_id == Auth::user()->id)
                    {
                        $user->is_me = true;
                    }else{
                        $user->is_me = false;
                    }
                }
                array_push ($res,$my_dpp);
            }
        }
        return $res;
    }

    public function get_dpp_overview(Dpp $dpp)
    {
        $dpp->type_name = $dpp->type->name;
        $dpp->participants = $dpp->participants;
        $dpp->stages = $dpp->stages;
        foreach($dpp->stages as $stage)
        {
            $ds = DppStage::find($stage->id);
            $stage->name = $ds->type->name;
            $stage->stage_status_name = $ds->status->name;
        }
        foreach($dpp->participants as $user)
        {
            $u = User::find($user->user_id);
            $user->fullname = $u->fullname;
            $user->user_id = $u->id;
            $r = Role::find($user->role_id);
            $user->rolename = $r->name;
            $user->role_id = $r->id;
            if ($u->id == Auth::user()->id)
            {
                $dpp->userRole = $r->id;
            }
        }

        return $dpp;
    }

    public function get_all_stage_types()
    {
       $st = StageType::all();
       return $st;
    }

    public function start_stage(Request $request)
    {
        $ds = DppStage::find($request->stage_id);
        $ds->stage_status_id = 3;
        $ds->save();
        $dpp = $ds->dpp;
        $dpp->status_id = 2;
        $dpp->save();

        if ($ds->stage_type_id == 1) {
            if ($dpp->zun_versions->count() == 0)
            {
                $zv = new ZunVersion;
                $zv->dpp_id = $dpp->id;
                $zv->author_id = Auth::user()->id;
                $zv->save();
                $dpp->zun_version_id = $zv->id;
                $dpp->save();
            }
        }

        if ($ds->stage_type_id == 2) {
            if ($dpp->om_versions->count() == 0)
            {
                $om = new OmVersion;
                $om->dpp_id = $dpp->id;
                $om->author_id = Auth::user()->id;
                $om->save();
                $dpp->om_version_id = $om->id;
                $dpp->save();

                if ($dpp->st_versions->count() == 0)
                {
                    $st = new StructureVersion;
                    $st->dpp_id = $dpp->id;
                    $st->author_id = Auth::user()->id;
                    $st->save();
                    $dpp->st_version_id = $st->id;
                    $dpp->save();
                    $st->rebuild();
                }
            }
        }

        if ($ds->stage_type_id == 3) {

        }

        if ($ds->stage_type_id == 4) {
            if ($dpp->ct_versions->count() == 0)
            {
                $ct = new ContentVersion;
                $ct->dpp_id = $dpp->id;
                $ct->author_id = Auth::user()->id;
                $ct->save();
                $dpp->ct_version_id = $ct->id;
                $dpp->save();
            }
        }

        if ($ds->stage_type_id == 6) {
            if ($dpp->ish_versions->count() == 0)
            {
                $iv = new IshVersion;
                $iv->dpp_id = $dpp->id;
                $iv->author_id = Auth::user()->id;
                $iv->save();
                $dpp->ish_version_id = $iv->id;
                $dpp->save();
            }
        }
    }

    public function delete_dpp(Request $request)
    {
        $dpp = Dpp::find($request->id);
        $dpp->current_stage_id = null;
        $dpp->save();
        foreach($dpp->stages as $stage)
        {
            DppStage::destroy($stage->id);
        }
        Dpp::destroy($request->id);
    }

    public function get_knowledges_to_ov(Dpp $dpp, OmVersion $ov)
    {
        $arr = $dpp->knowledges;
        foreach ($arr as $elem)
        {
            $elem->value = $elem->id;
            $elem->text = $elem->name;
            $kn = Knowledge::find($elem->id);
            $elem->questions = $kn->questions;
            foreach ($elem->questions as $q)
            {
                $q->type_name = $q->type->name;
            }
        }
        return $arr;
    }

    public function archive_dpp(Request $request)
    {
        $dpp = Dpp::find($request->id);
        $dpp->is_archieved = true;
        $dpp->save();
        return $dpp;
    }

    public function unarchive_dpp(Request $request)
    {
        $dpp = Dpp::find($request->id);
        $dpp->is_archieved = false;
        $dpp->save();
        return $dpp;
    }

    public function copy($dpp)
    {
        return DB::transaction(function() use ($dpp) {
            $dpp = Dpp::find($dpp);
            $newDpp = $dpp->replicate();
            $newDpp->name = '[копия] '.$newDpp->name;
            
            // Сохранить без событий, чтобы не создавались автоматические версии
            Dpp::withoutEvents(function() use ($newDpp) {
                $newDpp->push();
            });
            
            // Создать стадии и версии вручную
            $newDpp->createStages();
            $newDpp->create_iv();
            $newDpp->create_zun();
            $newDpp->create_om();
            $newDpp->create_st();
            $newDpp->create_ct();
            $newDpp->add_base_mtos();

            // Копировать только участников (стадии уже созданы выше)
            foreach ($dpp->participants as $participant) {
                $newParticipant = $participant->replicate();
                $newParticipant->dpp_id = $newDpp->id;
                $newParticipant->push();
            }

            // Заполнить IshVersion данными из старой версии
            $oldIV = IshVersion::find($dpp->ish_version_id);
            $newIV = $newDpp->ish_version;

            // Копировать связи many-to-many (справочные данные)
            foreach ($oldIV->ektses as $ekts) {
                $newIV->ektses()->attach($ekts->id);
            }
            foreach ($oldIV->ekses as $eks) {
                $newIV->ekses()->attach($eks->id);
            }
            foreach ($oldIV->world_skills as $ws) {
                $newIV->world_skills()->attach($ws->id);
            }
            foreach ($oldIV->corporate_requirements as $cr) {
                $newIV->corporate_requirements()->attach($cr->id);
            }
            foreach ($oldIV->prof_standarts as $ps)
            {
                $newIV->prof_standarts()->attach($ps->id);
            }
            foreach ($oldIV->fgoses as $fg)
            {
                $newIV->fgoses()->attach($fg->id);
            }


            foreach ($oldIV->nsis as $nsi)
            {
                $newNSI = $nsi->replicate();
                $newNSI->ish_version_id = $newIV->id;
                $newNSI->push();
            }

            // Импортировать компетенции, навыки, умения, знания и вопросы через метод import_competences
            $zunController = new ZunVersionController();
            $zunController->import_competences($newDpp, $dpp);

            // Импортировать задачи из старой ДПП
            $omController = new OmVersionController();
            $omController->import_tasks($newDpp, $dpp);

            // Применить структуру типологических частей из старой ДПП
            $zunController->apply_structure($newDpp, $dpp);

            $newDpp->current_stage_id = $newDpp->stages()->first()->id;
            $newDpp->save();

            return $newDpp->id;
        });
    }

    public function destroy2(Dpp $dpp)
    {
        $iv = IshVersion::find($dpp->ish_version_id);
        $iv->prof_levels()->detach(); $iv->ektses()->detach(); $iv->ekses()->detach();
        $iv->world_skills()->detach();  $iv->prof_standarts()->detach();
        $iv->corporate_requirements()->detach(); $iv->fgoses()->detach();
        foreach ($iv->typology_parts as $tp)
        {
            $tp->delete();
        }
        $iv->typology_parts()->delete();
        foreach ($iv->nsis as $nsi)
        {
            $nsi->delete();
        }
        $iv->nsis()->delete();
        $dpp->ish_version_id = null; $dpp->zun_version_id = null; $dpp->current_stage_id = null; $dpp->save();
        $dpp->participants()->delete();

        $dpp->stages()->delete();
        $dpp->knowledges()->delete();
        $dpp->abilities()->delete();
        $dpp->skills()->delete();
        $dpp->competences()->delete();
        $dpp->ish_versions()->delete();
        $dpp->zun_versions()->delete();
        Dpp::destroy($dpp->id);
    }

    /**
     * Полное каскадное удаление Dpp со всеми связанными данными
     *
     * @param Dpp $dpp
     * @return int ID удаленного Dpp
     */
    public function forceDeleteDpp(Dpp $dpp)
    {
        return DB::transaction(function() use ($dpp) {
            $dppId = $dpp->id;

            // Сначала обнуляем все ссылки в Dpp, чтобы избежать ошибок foreign key constraint
            $dpp->ish_version_id = null;
            $dpp->zun_version_id = null;
            $dpp->om_version_id = null;
            $dpp->st_version_id = null;
            $dpp->ct_version_id = null;
            $dpp->current_stage_id = null;
            $dpp->save();

            // 1. Удаление IshVersion и связанных данных
            foreach ($dpp->ish_versions as $iv) {
                // Отвязываем все many-to-many связи
                $iv->prof_levels()->detach();
                $iv->ektses()->detach();
                $iv->ekses()->detach();
                $iv->world_skills()->detach();
                $iv->prof_standarts()->detach();
                $iv->corporate_requirements()->detach();
                $iv->fgoses()->detach();
                $iv->dolg_kvals()->detach();

                // Удаляем QualificationRequirement
                foreach ($iv->qualification_requirements as $qr) {
                    $qr->delete();
                }

                // Удаляем ProfessionalObject
                foreach ($iv->professional_objects as $po) {
                    $po->delete();
                }

                // Удаляем DppTypologyPart
                // Сначала удаляем все связанные StructureSection (включая вложенные темы)
                foreach ($iv->typology_parts as $tp) {
                    // Удаляем все StructureSection с данным dtp_id (включая вложенные темы)
                    $sections = StructureSection::where('dtp_id', $tp->id)->get();
                    foreach ($sections as $section) {
                        // Отвязываем many-to-many связи
                        $section->knowledges()->detach();
                        $section->abilities()->detach();
                        $section->skills()->detach();

                        // Удаляем Lection (если есть)
                        foreach ($section->contents as $lection) {
                            // Отвязываем many-to-many связи
                            $lection->nsis()->detach();

                            // Удаляем LectionPart (Content)
                            foreach ($lection->parts as $part) {
                                $part->delete();
                            }

                            // Удаляем AdditionalFile
                            foreach ($lection->additional_files as $additionalFile) {
                                $additionalFile->delete();
                            }

                            // Удаляем файлы лекций из storage
                            $lectionPath = storage_path('app/lections/' . $lection->id);
                            if (File::exists($lectionPath)) {
                                File::deleteDirectory($lectionPath);
                            }

                            $lection->delete();
                        }

                        $section->delete();
                    }

                    // Отвязываем знания от DppTypologyPart
                    $tp->get_knowledges()->detach();

                    // Удаляем DppTypologyPart
                    $tp->delete();
                }

                // Удаляем Nsi записи
                foreach ($iv->nsis as $nsi) {
                    // Отвязываем many-to-many связи Nsi перед удалением
                    $nsi->skills()->detach();
                    $nsi->abilities()->detach();
                    $nsi->knowledges()->detach();
                    $nsi->tasks()->detach();
                    $nsi->delete();
                }

                // Удаляем IshVersion
                $iv->delete();
            }

            // 2. Удаление ZunVersion и связанных данных
            foreach ($dpp->zun_versions as $zv) {
                // Сначала удаляем все Competence с forceDelete (включая soft deleted)
                // Это важно, так как Competence использует SoftDeletes и может блокировать удаление ZunVersion
                $competences = Competence::withTrashed()->where('zun_version_id', $zv->id)->get();
                foreach ($competences as $competence) {
                    // Удаляем связанные Skills внутри Competence
                    foreach ($competence->skills()->withTrashed()->get() as $skill) {
                        $skill->nsis()->detach();
                        $skill->sections()->detach();
                        foreach ($skill->task_subjects as $taskSubject) {
                            foreach ($taskSubject->objects as $taskObject) {
                                $taskObject->delete();
                            }
                            $taskSubject->delete();
                        }
                        // Удаляем связанные Abilities внутри Skill
                        foreach ($skill->abilities()->withTrashed()->get() as $ability) {
                            $ability->nsis()->detach();
                            $ability->sections()->detach();
                            foreach ($ability->task_subjects as $taskSubject) {
                                foreach ($taskSubject->objects as $taskObject) {
                                    $taskObject->delete();
                                }
                                $taskSubject->delete();
                            }
                            // Удаляем Knowledge внутри Ability
                            foreach ($ability->knowledges()->withTrashed()->get() as $knowledge) {
                                $knowledge->links()->detach();
                                $knowledge->get_dtps()->detach();
                                $knowledge->sections()->detach();
                                $knowledge->nsis()->detach();
                                foreach ($knowledge->questions as $question) {
                                    $question->single_choice_answers()->delete();
                                    $question->multi_choice_answers()->delete();
                                    $question->free_choice_answers()->delete();
                                    $question->sequence_choice_answers()->delete();
                                    $question->accordance_choice_answers()->delete();
                                    $question->delete();
                                }
                                $knowledge->forceDelete();
                            }
                            $ability->forceDelete();
                        }
                        $skill->forceDelete();
                    }
                    // Удаляем связанные Abilities внутри Competence (не через Skill)
                    foreach ($competence->abilities()->withTrashed()->get() as $ability) {
                        $ability->nsis()->detach();
                        $ability->sections()->detach();
                        foreach ($ability->task_subjects as $taskSubject) {
                            foreach ($taskSubject->objects as $taskObject) {
                                $taskObject->delete();
                            }
                            $taskSubject->delete();
                        }
                        // Удаляем Knowledge внутри Ability
                        foreach ($ability->knowledges()->withTrashed()->get() as $knowledge) {
                            $knowledge->links()->detach();
                            $knowledge->get_dtps()->detach();
                            $knowledge->sections()->detach();
                            $knowledge->nsis()->detach();
                            foreach ($knowledge->questions as $question) {
                                $question->single_choice_answers()->delete();
                                $question->multi_choice_answers()->delete();
                                $question->free_choice_answers()->delete();
                                $question->sequence_choice_answers()->delete();
                                $question->accordance_choice_answers()->delete();
                                $question->delete();
                            }
                            $knowledge->forceDelete();
                        }
                        $ability->forceDelete();
                    }
                    // Физически удаляем Competence из базы данных
                    $competence->forceDelete();
                }

                // Удаляем остальные элементы верхнего уровня (не связанные с Competence)
                // Knowledge верхнего уровня
                foreach (Knowledge::withTrashed()->where('zun_version_id', $zv->id)->whereNull('ability_id')->get() as $knowledge) {
                    $knowledge->links()->detach();
                    $knowledge->get_dtps()->detach();
                    $knowledge->sections()->detach();
                    $knowledge->nsis()->detach();
                    foreach ($knowledge->questions as $question) {
                        $question->single_choice_answers()->delete();
                        $question->multi_choice_answers()->delete();
                        $question->free_choice_answers()->delete();
                        $question->sequence_choice_answers()->delete();
                        $question->accordance_choice_answers()->delete();
                        $question->delete();
                    }
                    $knowledge->forceDelete();
                }

                // Ability верхнего уровня (не связанные с Competence или Skill)
                foreach (Ability::withTrashed()->where('zun_version_id', $zv->id)->whereNull('competence_id')->whereNull('skill_id')->get() as $ability) {
                    $ability->nsis()->detach();
                    $ability->sections()->detach();
                    foreach ($ability->task_subjects as $taskSubject) {
                        foreach ($taskSubject->objects as $taskObject) {
                            $taskObject->delete();
                        }
                        $taskSubject->delete();
                    }
                    foreach ($ability->knowledges()->withTrashed()->get() as $knowledge) {
                        $knowledge->links()->detach();
                        $knowledge->get_dtps()->detach();
                        $knowledge->sections()->detach();
                        $knowledge->nsis()->detach();
                        foreach ($knowledge->questions as $question) {
                            $question->single_choice_answers()->delete();
                            $question->multi_choice_answers()->delete();
                            $question->free_choice_answers()->delete();
                            $question->sequence_choice_answers()->delete();
                            $question->accordance_choice_answers()->delete();
                            $question->delete();
                        }
                        $knowledge->forceDelete();
                    }
                    $ability->forceDelete();
                }

                // Skill верхнего уровня (не связанные с Competence)
                foreach (Skill::withTrashed()->where('zun_version_id', $zv->id)->whereNull('competence_id')->get() as $skill) {
                    $skill->nsis()->detach();
                    $skill->sections()->detach();
                    foreach ($skill->task_subjects as $taskSubject) {
                        foreach ($taskSubject->objects as $taskObject) {
                            $taskObject->delete();
                        }
                        $taskSubject->delete();
                    }
                    foreach ($skill->abilities()->withTrashed()->get() as $ability) {
                        $ability->nsis()->detach();
                        $ability->sections()->detach();
                        foreach ($ability->task_subjects as $taskSubject) {
                            foreach ($taskSubject->objects as $taskObject) {
                                $taskObject->delete();
                            }
                            $taskSubject->delete();
                        }
                        foreach ($ability->knowledges()->withTrashed()->get() as $knowledge) {
                            $knowledge->links()->detach();
                            $knowledge->get_dtps()->detach();
                            $knowledge->sections()->detach();
                            $knowledge->nsis()->detach();
                            foreach ($knowledge->questions as $question) {
                                $question->single_choice_answers()->delete();
                                $question->multi_choice_answers()->delete();
                                $question->free_choice_answers()->delete();
                                $question->sequence_choice_answers()->delete();
                                $question->accordance_choice_answers()->delete();
                                $question->delete();
                            }
                            $knowledge->forceDelete();
                        }
                        $ability->forceDelete();
                    }
                    $skill->forceDelete();
                }

                // Удаляем ZunVersion
                $zv->delete();
            }

            // 3. Удаление OmVersion и связанных данных
            foreach ($dpp->om_versions as $ov) {
                // Удаляем Question (не связанные с Knowledge, так как они уже удалены)
                foreach ($ov->questions as $question) {
                    // Удаляем ответы на вопросы
                    $question->single_choice_answers()->delete();
                    $question->multi_choice_answers()->delete();
                    $question->free_choice_answers()->delete();
                    $question->sequence_choice_answers()->delete();
                    $question->accordance_choice_answers()->delete();
                    $question->delete();
                }

                // Удаляем Task (с каскадным удалением подчиненных)
                foreach ($ov->tasks as $task) {
                    // Отвязываем many-to-many связи
                    $task->mtos()->detach();
                    $task->nsis()->detach();

                    // Удаляем TaskStep
                    foreach ($task->steps as $step) {
                        $step->delete();
                    }

                    // Удаляем TaskQuestion
                    foreach ($task->questions as $taskQuestion) {
                        $taskQuestion->delete();
                    }

                    // Удаляем TaskSpecification
                    if ($task->specification) {
                        $task->specification->delete();
                    }

                    // Удаляем TaskAdditionalFile
                    foreach ($task->additional_files as $additionalFile) {
                        $additionalFile->delete();
                    }

                    // Удаляем TaskSubject и TaskObject (через boot метод Task)
                    foreach ($task->subjects as $taskSubject) {
                        foreach ($taskSubject->objects as $taskObject) {
                            $taskObject->delete();
                        }
                        $taskSubject->delete();
                    }

                    // Удаляем TaskObject напрямую (если есть)
                    foreach ($task->objects as $taskObject) {
                        $taskObject->delete();
                    }

                    $task->delete();
                }

                // Удаляем OmVersion
                $ov->delete();
            }

            // 4. Удаление StructureVersion и связанных данных
            foreach ($dpp->st_versions as $sv) {
                // Получаем ВСЕ StructureSection для данного st_version_id (включая вложенные темы)
                $allSections = StructureSection::where('st_version_id', $sv->id)->get();
                
                // Удаляем все StructureSection (boot метод каскадно удалит вложенные секции и Lection)
                foreach ($allSections as $section) {
                    // Отвязываем many-to-many связи
                    $section->knowledges()->detach();
                    $section->abilities()->detach();
                    $section->skills()->detach();

                    // Удаляем Lection (если есть)
                    foreach ($section->contents as $lection) {
                        // Отвязываем many-to-many связи
                        $lection->nsis()->detach();

                        // Удаляем LectionPart (Content)
                        foreach ($lection->parts as $part) {
                            $part->delete();
                        }

                        // Удаляем AdditionalFile
                        foreach ($lection->additional_files as $additionalFile) {
                            $additionalFile->delete();
                        }

                        // Удаляем файлы лекций из storage
                        $lectionPath = storage_path('app/lections/' . $lection->id);
                        if (File::exists($lectionPath)) {
                            File::deleteDirectory($lectionPath);
                        }

                        $lection->delete();
                    }

                    $section->delete();
                }

                // Удаляем StructureVersion
                $sv->delete();
            }

            // 5. Удаление ContentVersion и связанных данных
            foreach ($dpp->ct_versions as $cv) {
                // Lection уже удалены через StructureSection, но проверим на всякий случай
                // (если есть Lection напрямую связанные с ContentVersion)
                $lections = Lection::where('ct_version_id', $cv->id)->get();
                foreach ($lections as $lection) {
                    // Отвязываем many-to-many связи
                    $lection->nsis()->detach();

                    // Удаляем LectionPart (Content)
                    foreach ($lection->parts as $part) {
                        $part->delete();
                    }

                    // Удаляем AdditionalFile
                    foreach ($lection->additional_files as $additionalFile) {
                        $additionalFile->delete();
                    }

                    // Удаляем файлы лекций из storage
                    $lectionPath = storage_path('app/lections/' . $lection->id);
                    if (File::exists($lectionPath)) {
                        File::deleteDirectory($lectionPath);
                    }

                    $lection->delete();
                }

                // Удаляем ContentVersion
                $cv->delete();
            }

            // 6. Удаление остальных прямых связей Dpp
            // Удаляем Mto (с отвязкой task_mtos)
            foreach ($dpp->mtos as $mto) {
                $mto->tasks()->detach();
                $mto->delete();
            }

            // Удаляем Designer
            foreach ($dpp->designers as $designer) {
                $designer->delete();
            }

            // Удаляем DppStage
            foreach ($dpp->stages as $stage) {
                $stage->delete();
            }

            // Удаляем DppUserRole
            foreach ($dpp->participants as $participant) {
                $participant->delete();
            }

            // 7. Удаляем Dpp (forceDelete для полного удаления, так как используется SoftDeletes)
            // Ссылки уже обнулены в начале функции
            $dpp->forceDelete();

            return $dppId;
        });
    }

    public function get_signatory(Dpp $dpp) {
        $result["signatoryJob"] = $dpp->signatory_job ?? "";
        $result["signatoryFio"] = $dpp->signatory_fio ?? "";

        return json_encode($result);
    }

    public function set_signatory(Dpp $dpp, Request $request) {
        $dpp->signatory_job = $request['signatoryJob'];
        $dpp->signatory_fio = $request['signatoryFio'];
        $dpp->save();
        return $this->get_signatory($dpp);
    }
}
