<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StructureVersion extends Model
{
    public function get_sections()
    {
        return $this->hasMany('App\StructureSection','st_version_id');
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
}
