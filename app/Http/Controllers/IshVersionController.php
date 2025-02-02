<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\IshVersion;
use App\Dpp;
use App\ProfLevel;
use App\NsiType;
use App\Nsi;
use App\Typology;
use App\Ministry;
use App\TypologyPart;
use App\DppTypologyPart;
use App\CorporateRequirement;
use App\Http\Resources\IshVersionResource;
use App\Http\Resources\ProgramDirectionResource;
use Illuminate\Support\Facades\DB;
use Auth;
class IshVersionController extends Controller
{
    public function get_ish_version_data(Dpp $dpp,$iv)
    {
        $arr = [];
        if ($dpp->ish_version_id == null) {
            $iv = $dpp->create_iv();
        }else{
            $iv = IshVersion::find($iv);
        }
        if ($iv->req_user_edulevel == null) {$iv->req_user_edulevel=" ";}
        if ($iv->req_user_kval == null) {$iv->req_user_kval=" ";}
        if ($iv->target == null) {$iv->target=" ";}
        foreach ($iv->prof_levels as $el)
        {
            $arr[] = $el->id;
        }
        $tls = Typology::all();
        foreach ($tls as $tl)
        {
            $tl->parts = $tl->typology_parts;
        }
        $iv->typologies = $tls;
        $iv->typology = $iv->typology_id;
        $iv->dpp_parts = $iv->typology_parts;
        $iv->pl = $arr;
        $iv->prof_standarts = $iv->prof_standarts;
        $iv->dolg_kvals = $iv->dolg_kvals;
        $iv->fgoses = $iv->fgoses;
        $iv->ektses = $iv->ektses;
        $iv->ekses = $iv->ekses;
        $iv->world_skills = $iv->world_skills;
        $iv->corporate_requirements = $iv->corporate_requirements;
        $nsis = Nsi::where('ish_version_id',$iv->id)->with('type')->get();
        $iv->nsis = $nsis;
        $iv->total_hours = $dpp->total_hours;
        $iv->type = $dpp->dpp_type_id;
        $iv->ministries = Ministry::all();
        // $nsis = Nsi::where('ish_version_id',$iv->id)->orderBy(
        //     NsiType::select('position')
        //         ->where('id','nsis.id')
        //         ->limit(1));
        //$iv->nsis2 = $nsis->hasType()->get();
        
        // $iv->nsis = Nsi::where('ish_version_id',$iv->id)->with(['type' => function ($q){
        //     $q->orderBy('position');
        // }])->get();
        return json_encode($iv);
    }


    function get_ish_version_data2(Dpp $dpp, IshVersion $iv) {
        return new IshVersionResource($iv);
    }

    public function get_prof_levels()
    {
        $pl = ProfLevel::all();
        foreach ($pl as $el)
        {
            $el->value = $el->id;
            $el->text = $el->name;
        }
        return $pl;
    }


    public function create_parts()
    {
        $dpps = Dpp::all();
        foreach ($dpps as $dpp)
        {
        try 
        {
        //$dpp = Dpp::findOrFail($dpp_id);
        $iv = IshVersion::findOrFail($dpp->ish_version_id);
        $typology = Typology::findOrFail($iv->typology_id);
        $parts = $typology->typology_parts;
        $k = 1;
        foreach ($parts as $part)
        {
            $dtp = new DppTypologyPart;
            $dtp->dpp_id = $dpp->id;
            $dtp->typology_id = $typology->id;
            $dtp->ish_version_id = $iv->id;
            $dtp->name = $part->name;
            $dtp->not_necessary = $part->not_necessary;
            $dtp->position = $k;
            $dtp->save();
            $k++;
        }
        }catch(Exception  $e) {echo $e;}
        }
    }

    public function select_profstandarts(IshVersion $iv, Request $request)
    {
        $iv->prof_standarts()->sync($request->data);
        return $iv->prof_standarts;
    }

    public function select_corporate_requirements(IshVersion $iv, Request $request)
    {
        $iv->corporate_requirements()->sync($request->data);
        return $iv->corporate_requirements;
    }

    public function select_ektses(IshVersion $iv, Request $request)
    {
        $iv->ektses()->sync($request->data);
        return $iv->ektses;
    }

    public function select_ekses(IshVersion $iv, Request $request)
    {
        $iv->ekses()->sync($request->data);
        return $iv->ekses;
    }

    public function select_world_skills(IshVersion $iv, Request $request)
    {
        $iv->world_skills()->sync($request->data);
        return $iv->world_skills;
    }

    public function select_fgoses(IshVersion $iv, Request $request)
    {
        $iv->fgoses()->sync($request->data);
        return $iv->fgoses;
    }

    public function unselect_qual_based (IshVersion $iv, Request $request)
    {
        switch ($request->type)
        {
            case "prof": $iv->prof_standarts()->detach($request->id); return $iv->prof_standarts; break; 
            case "fgos": $iv->fgoses()->detach($request->id); return $iv->fgoses; break; 
            case "etkc": $iv->ektses()->detach($request->id); return $iv->ektses; break; 
            case "ekc": $iv->ekses()->detach($request->id); return $iv->ekses; break; 
            case "ws": $iv->world_skills()->detach($request->id); return $iv->world_skills; break; 
            case "cr": $iv->corporate_requirements()->detach($request->id); return $iv->corporate_requirements; break;
        }
    }

    public function update_requirements(IshVersion $iv, Request $request)
    {
        $iv->prof_levels()->sync(array_column($request->profLevels,'id'));
        $iv->req_user_kval = $request->reqQualification;
        if ($request->has('qualification')) {
            $iv->qualification = $request->qualification;
        }
        $iv->save();
        return response()->json(['message'=>'success'],200);
    }

    public function update_period(IshVersion $iv, Request $request)
    {
        $dpp = Dpp::find ($iv->dpp_id);
        $dpp->total_hours = $request->input('total_hours');
        $dpp->save();
        
        $iv->edu_period_name = $request->input('edu_period_name');
        $iv->edu_period_duration = $request->input('edu_period_duration');
        $iv->save();

        return response()->json(['message'=>'success'],200);
    }

    public function update_description(IshVersion $iv, Request $request)
    {
        $desc = $request->programDescription;
        $iv->annotationDescription = $desc;
        $iv->save();
        return response()->json(['message'=>'success'],200);
    }


    public function update_hours(IshVersion $iv, Request $request)
    {
        $dpp = $iv->dpp;
        $dpp->total_hours = $request->total_hours;
        $dpp->save();
        return response()->json(['message'=>'success'],200);
    }

    public function update_form(IshVersion $iv, Request $request)
    {
        $iv->edu_form = $request->input('edu_form');
        $iv->edu_form_dot = $request->input('edu_form_dot');
        $iv->edu_practic = $request->input('edu_practic');
        $iv->save();
        return response()->json(['message'=>'success'],200);
    }

    public function update_digital_sphere(IshVersion $iv, Request $request)
    {
        $iv->digital_sphere_id = $request->input('digital_sphere.id');
        $iv->save();
        return response()->json(['message'=>'success'],200);
    }

    public function update_direction(IshVersion $iv, Request $request)
    {
        $iv->direction_id = $request->currentDirection;
        $iv->save();
        return new ProgramDirectionResource($iv->direction);
    }

 

    public function save_annotation(IshVersion $iv, Request $request)
    {
        $annotation = $request->annotation;
        $iv->annotationDescription = $annotation["annotationDescription"];
        $iv->annotationRequirements = $annotation["annotationRequirements"];
        $iv->annotationTargets = $annotation["annotationTargets"];
        $iv->annotationResults = $annotation["annotationResults"];
        $iv->save();
        return $iv;
    }

    /* OLD */ 
    public function select_dolgkvals(Dpp $dpp, IshVersion $iv, Request $request)
    {
        
        $iv->dolg_kvals()->sync($request->data);
        return $iv->dolg_kvals;
    }

    public function update_ish_version_data(Dpp $dpp,IshVersion $iv, Request $request)
    {
        $iv->req_user_edulevel = $request->ish_data["req_user_edulevel"];
        $iv->req_user_kval = $request->ish_data["req_user_kval"];
        $iv->target = $request->ish_data["target"];
        $iv->make_new_competence = $request->ish_data["make_new_competence"];
        if ($iv->typology_id != $request->ish_data["typology"])
        {
            
            foreach($iv->typology_parts as $part)
            {
                $part->get_knowledges()->detach();
                DppTypologyPart::destroy($part->id);
            }
            $typology = Typology::find($request->ish_data["typology"]);
            $parts = $typology->typology_parts;
            $k = 1;
            foreach ($parts as $part)
            {
                $dtp = new DppTypologyPart;
                $dtp->dpp_id = $dpp->id;
                $dtp->typology_id = $typology->id;
                $dtp->ish_version_id = $iv->id;
                $dtp->name = $part->name;
                $dtp->not_necessary = $part->not_necessary;
                $dtp->position = $k;
                $dtp->save();
                $k++;
            }
            $iv->typology_id = $request->ish_data["typology"];
        }
        
        $iv->save();
        $iv->prof_levels()->sync($request->ish_data["pl"]);
        return $this->get_ish_version_data($dpp,$iv);
    }

    public function get_nsi_types()
    {
        $types = NsiType::all();
        return $types;
    }

    public function get_nsis(IshVersion $iv, Request $request)
    {
        $nsis = $iv->nsis;
        foreach ($nsis as $nsi)
        {
            $nsi->type_name = $nsi->type->name;
        }
        return $nsis;
    }

    public function get_nsi(Nsi $nsi)
    {
        return $nsi;
    }

    public function add_nsi(Request $request)
    {
        $data = $request->nsi_data;
        $nsi = new Nsi;
        $nsi->type_id = $data["type"];
        $nsi->name = $data["name"];
        $nsi->authors = $data["authors"];
        $nsi->output = $data["output"];
        $nsi->url = $data["url"];
        $nsi->fullname = $data["fullname"];
        $nsi->old_name = $data["old_name"];
        $nsi->start_date = $data["start_date"];
        $nsi->accept_date = $data["accept_date"];
        $nsi->accept_number = $data["accept_number"];
        $nsi->accept_odm = $data["accept_odm"];
        $nsi->odm_number = $data["odm_number"];
        $nsi->npa_type = $data["npa_type"];
        $nsi->city = $data["city"];
        $nsi->year = $data["year"];
        $nsi->pages = $data["pages"];

        $nsi->ish_version_id = $request->ish_version_id;
        $nsi->author_id = Auth::user()->id;
        $nsi->save();
        return $nsi;
    }

    public function update_nsi(Request $request)
    {
        $data = $request->nsi_data;
        $nsi = Nsi::find($data["id"]);
        $nsi->type_id = $data["type_id"];
        $nsi->name = $data["name"];
        $nsi->authors = $data["authors"];
        $nsi->output = $data["output"];
        $nsi->url = $data["url"];
        $nsi->fullname = $data["fullname"];
        $nsi->old_name = $data["old_name"];
        $nsi->start_date = $data["start_date"];
        $nsi->accept_date = $data["accept_date"];
        $nsi->accept_number = $data["accept_number"];
        $nsi->accept_odm = $data["accept_odm"];
        $nsi->odm_number = $data["odm_number"];
        $nsi->npa_type = $data["npa_type"];
        $nsi->city = $data["city"];
        $nsi->year = $data["year"];
        $nsi->pages = $data["pages"];
        $nsi->author_id = Auth::user()->id;
        $nsi->save();
        return $nsi;
    }

    public function remove_nsi(Request $request)
    {
        Nsi::destroy($request->nsi_id);
        return $request->nsi_id;
    }
    /* --- */

    public function update_sphere_field(IshVersion $iv, Request $request) {
        $iv->professional_field_id = $request->input('qualification_field.id');
        $iv-> professional_sphere = $request->input('qualification_sphere');
        $iv->save();
        return response()->json(['message' => 'OK'], 200);
    }
   
}
