<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Lection;

class StructureVersion extends Model
{
    public function get_sections()
    {
        return $this->hasMany('App\StructureSection','st_version_id');
    }

    public function parent_sections()
    {
        return $this->hasMany('App\StructureSection','st_version_id')->where('parent_id',null);
    }

    public function dpp()
    {
        return $this->belongsTo('App\Dpp','dpp_id');
    }

    public function rebuild()
    {
        $sections = $this->get_sections;
        $dpp = $this->dpp;
        foreach ($sections as $section)
        {
            $themes = $section->themes;
            foreach ($themes as $theme)
            {
                $theme->knowledges()->detach();
                $theme->abilities()->detach();
                $theme->skills()->detach();
                foreach ($theme->contents as $lection)
                {
                    Lection::destroy($lection->id);
                }
                StructureSection::destroy($theme->id);
            }
            $section->knowledges()->detach();
            $section->abilities()->detach();
            $section->skills()->detach();
            StructureSection::destroy($section->id);
        }
        $n = 1;
        foreach($dpp->typology_parts as $tp)
        {
            $section = new StructureSection;
            $section->name = $tp->name;
            $section->position = $n;
            $section->st_version_id = $this->id;
            $section->dtp_id = $tp->id;
            $section->save();
            $n++;
            $m=1;
            foreach ($tp->get_knowledges as $kn)
            {
                $theme = new StructureSection;
                $arr = explode(" ",$kn["what"]);

                //$fc = mb_strtoupper(mb_substr($kn["what"], 0, 1));
                //$work = $fc.mb_substr($kn["what"], 1);
                //$arr = explode(" ",$work);

                if (substr($arr[0], 0, 1) == ',')
                {
                    array_shift($arr);
                }
                $work = $arr[0];
                $word = DB::table('nouns_morf')->where('word', $work )->where('wcase','вин')->first();
                if ($word)
                {
                    $parent = DB::table('nouns_morf')->where('code', $word->code_parent )->first();
                    $arr[0] = $parent->word;
                }
                if (substr($work, -4) == 'ую') { $arr[0]= substr($work, 0,strlen($work)-4)."ая";}
                if (substr($work, -4) == 'юю') { $arr[0]= substr($work, 0,strlen($work)-4)."яя";}
                $fc = mb_strtoupper(mb_substr($arr[0], 0, 1));
                $arr[0] = $fc.mb_substr($arr[0], 1);
                $theme->name = implode(" ",$arr);
                $theme->position = $m;
                $theme->st_version_id = $this->id;
                $theme->parent_id = $section->id;
                $theme->dtp_id = $tp->id;
                $theme->knowledge_id = $kn->id;
                $theme->save();
                $m++;
                $theme->knowledges()->attach($kn->id);
                $section->knowledges()->attach($kn->id);
            }

        }
        $section = new StructureSection;
        $section->name = 'Итоговая аттестация';
        $section->position = $n;
        $section->st_version_id = $this->id;
        $section->save();
    }

    public function reattach_practice()
    {
        $sections = StructureSection::where('st_version_id',$this->id)->where('parent_id',null)->get();
        foreach ($sections as $section)
        {
            $section->abilities()->detach();
            $section->skills()->detach();
        }
        $themes = StructureSection::where('st_version_id',$this->id)->where('parent_id','<>',null)->get();
        foreach ($themes as $theme)
        {
            $theme->abilities()->detach();
            $theme->skills()->detach();
            if ($theme->practice_hours > 0 || $theme->lab_hours > 0)
            {
                $section = StructureSection::find($theme->parent_id);
                $knowledges = $theme->knowledges;
                foreach ($knowledges as $knowledge)
                {
                    if ($knowledge->ability_id != null)
                    {
                        $ability = Ability::find($knowledge->ability_id);
                        $theme->abilities()->syncWithoutDetaching($ability->id);
                        $section->abilities()->syncWithoutDetaching($ability->id);
                        if ($ability->skill_id != null)
                        {
                            $theme->skills()->syncWithoutDetaching($ability->skill_id);
                            $section->skills()->syncWithoutDetaching($ability->skill_id);
                        }
                    }
                }
            }
        }

    }

    public function recount_section_hours()
    {
        $parent_sections = StructureSection::where('st_version_id',$this->id)->where('parent_id',null)->where('name','<>','Итоговая аттестация')->get();
        foreach ($parent_sections as $section)
        {
            $section->lection_hours = 0; $section->practice_hours = 0; $section->self_hours = 0;
            $section->total_hours = 0; $section->lab_hours = 0; $section->attestation_hours = 0;
            foreach ($section->themes as $theme)
            {
                $section->lection_hours += $theme->lection_hours;
                $section->practice_hours += $theme->practice_hours;
                $section->self_hours += $theme->self_hours;
                $section->total_hours += $theme->total_hours;
                $section->lab_hours += $theme->lab_hours;
                $section->attestation_hours += $theme->attestation_hours;
            }
            $section->save();
        }
    }

    public function recount_all_sections_pp()
    {
        $parent_sections = StructureSection::where('st_version_id',$this->id)->where('parent_id',null)->where('name','<>','Итоговая аттестация')->get();
        foreach ($parent_sections as $section)
        {
           $section->recount_section();
        }
    }

}
