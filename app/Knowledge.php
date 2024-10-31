<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\StructureSection;
use App\StructureVersion;
use App\Lection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class Knowledge extends Model
{
    use SoftDeletes;

    public static function boot() {
        parent::boot();

        static::deleting(function($kn)
        {
            $kn->moveUpBelowKnowledges();
            $kn->links()->detach();
            $kn->get_dtps()->detach();
            $kn->nsis()->detach();

            $sv = $kn->dpp->st_version_id;
            if ($sv != null)
            {
                $theme = StructureSection::where('knowledge_id',$kn->id)->get()->first();
                $sv = $theme->st_version;
                if ($theme)
                {
                    $theme->delete();
                }
                if ($sv->dpp->dpp_type_id == 2) {
                    $sv->recount_all_sections_pp();
                }
                if ($sv->dpp->dpp_type_id == 1) {
                    $sv->recount_section_hours_pk();
                }
            }
        });
        static::updated(function($kn)
        {
            $sv = $kn->dpp->st_version_id;
            if ($sv != null  && $kn->dtps->count() != 0)
            {
                $theme = StructureSection::where('knowledge_id',$kn->id)->get()->first();
                if ($theme)
                {
                    $kn->update_theme($sv);
                }else{
                    $kn->add_theme($sv);
                }
            }
        });
    }


    function questions () {
        return $this->hasMany('App\Question','knowledge_id');
    }

    function nsis () {
        return $this->belongsToMany('App\Nsi', 'knowledge_nsis', 'knowledge_id', 'nsi_id');
    }

    public function links()
    {
        return $this->belongsToMany('App\Ability', 'knowledge_links', 'knowledge_id', 'ability_id');
    }

    public function get_dtps()
    {
        return $this->belongsToMany('App\DppTypologyPart', 'knowledge_dtp', 'knowledge_id', 'dtp_id');
    }

    public function dtps()
    {
        return $this->belongsToMany('App\DppTypologyPart', 'knowledge_dtp', 'knowledge_id', 'dtp_id')->withPivot(['knowledge_id','dtp_id','position']);
    }

    public function sections()
    {
        return $this->belongsToMany('App\StructureSection', 'knowledge_section', 'knowledge_id', 'section_id');
    }

    public function dpp()
    {
        return $this->belongsTo('App\Dpp', 'dpp_id');
    }

    public function theme()
    {
        return $this->hasOne('App\StructureSection','knowledge_id');
    }

    public function lection()
    {
        return $this->hasOneThrough(
            'App\Lection',
            'App\StructureSection',
            'knowledge_id', // Foreign key on the cars table...
            'section_id', // Foreign key on the owners table...
            'id', // Local key on the mechanics table...
            'id' // Local key on the cars table...
        );
    }

    public function add_theme($sv)
    {
        $theme = new StructureSection;
        $arr = explode(" ",$this->what);

        if (substr($arr[0], 0, 1) == ',')
        {
            array_shift($arr);
        }

        if ($this->getWordType($arr[0]) == 'noun') {
            $arr[0] = $this->padej($arr[0]);
            if (isset($arr[1]) && $arr[1] == 'и') {
                $arr[2] = $this->padej($arr[2]);
            }
        } elseif ($this->getWordType($arr[0]) == 'adj') {
            $arr[0] = $this->padej($arr[0]);
            if (isset($arr[1]) && $arr[1] == 'и') {
                if (isset($arr[2])) {
                    $arr[2] = $this->padej($arr[2]);
                }
                if (isset($arr[3])) {
                    $arr[3] = $this->padej($arr[3]);
                }
            }
            if (isset($arr[1])) {
                $arr[1] = $this->padej($arr[1]);
            }
        }



        // $word = DB::table('nouns_morf')->where('word', $work )->where('wcase','вин')->first();
        // if ($word)
        // {
        //     $parent = DB::table('nouns_morf')->where('code', $word->code_parent )->first();
        //     $arr[0] = $parent->word;
        // }

        $fc = mb_strtoupper(mb_substr($arr[0], 0, 1));
        $arr[0] = $fc.mb_substr($arr[0], 1);
        $theme->name = implode(" ",$arr);
        $theme->st_version_id = $sv;

        $theme->knowledge_id = $this->id;
        foreach ($this->dtps as $dtp) {
            $theme->dtp_id = $dtp->id;
            $theme->position = $dtp->pivot->position;
            $section = StructureSection::where('dtp_id',$dtp->id)->where('parent_id',null)->get()->first();
            $theme->parent_id = $section->id;
            $section->knowledges()->syncWithoutDetaching($this->id);
        }

        $theme->save();
        $theme->knowledges()->attach($this->id);

        if ($this->hidden_lection_id != null)
        {
            $hidden_lection = Lection::findOrFail($this->hidden_lection_id);
            $new_lection = $hidden_lection->replicate();
            $new_lection->section_id = $theme->id;
            $new_lection->ct_version_id = $theme->st_version->dpp->ct_version->id;
            $new_lection->push();
            File::copyDirectory(storage_path('app/lections/'.$hidden_lection->id), storage_path('app/lections/'.$new_lection->id));
        }
    }

    public function update_theme($sv)
    {
        $theme = StructureSection::where('knowledge_id',$this->id)->first();
        $arr = explode(" ",$this->what);

        if (substr($arr[0], 0, 1) == ',')
        {
            array_shift($arr);
        }

        if ($this->getWordType($arr[0]) == 'noun') {
            $arr[0] = $this->padej($arr[0]);
            if (isset($arr[1]) && $arr[1] == 'и') {
                $arr[2] = $this->padej($arr[2]);
            }
        } elseif ($this->getWordType($arr[0]) == 'adj') {
            $arr[0] = $this->padej($arr[0]);
            if (isset($arr[1]) && $arr[1] == 'и') {
                if (isset($arr[2])) {
                    $arr[2] = $this->padej($arr[2]);
                }
                if (isset($arr[3])) {
                    $arr[3] = $this->padej($arr[3]);
                }
            }
            if (isset($arr[1])) {
                $arr[1] = $this->padej($arr[1]);
            }
        }

        $fc = mb_strtoupper(mb_substr($arr[0], 0, 1));
        $arr[0] = $fc.mb_substr($arr[0], 1);

        $theme->name = implode(" ",$arr);

        foreach ($this->dtps as $dtp) {
            $theme->dtp_id = $dtp->id;
            $theme->position = $dtp->pivot->position;
            $section = StructureSection::where('dtp_id',$dtp->id)->where('parent_id',null)->get()->first();
            $theme->parent_id = $section->id;
            $section->knowledges()->sync($this->id);
            $theme->save();
            $theme->knowledges()->sync($this->id);
        }

        $sv = StructureVersion::find($sv);
        if ($sv->dpp->dpp_type_id == 2) {
            $sv->recount_all_sections_pp();
        }
        if ($sv->dpp->dpp_type_id == 1) {
            $sv->recount_section_hours_pk();
        }
    }


    public function moveUpBelowKnowledges()
    {
        //в списке типового раздела
        $dtp = $this->dtps()->first();
        if ($dtp != null)
        {
            $position = $dtp->pivot->position;
            DB::table('knowledge_dtp')
              ->where('dtp_id', $dtp->id)
              ->where('position','>', $position)->decrement('position');
                //dd($affected);
              //->decrement('position', 1);
        }


        //в общем списке
        if ($this->is_through == 0)
        {
            $below_kns = Knowledge::where('zun_version_id',$this->dpp->zun_version_id)
            ->where('ability_id',$this->ability_id)->where('position','>',$this->position)->get();
        }else{
            $below_kns = Knowledge::where('zun_version_id',$this->dpp->zun_version_id)
            ->where('is_through',1)->where('position','>',$this->position)->get();
        }
        foreach ($below_kns as $knowledge)
        {
            $knowledge->position = $knowledge->position-1;
            $knowledge->save();
        }
    }

    public function setPositionAndParent($pid)
    {
        if ($pid == 'th')
        {
            $knc = Knowledge::where('zun_version_id',$this->zun_version_id)->where('is_through',1)->get()->count();
            $this->is_through = true;
            $this->ability_id = null;
            $this->position = $knc+1;
        }else{
            $this->is_through = false;
            $parent_node = substr($pid,1);
            $knc = Knowledge::where('zun_version_id',$this->zun_version_id)->where('ability_id',$parent_node)->get()->count();
            $this->ability_id = $parent_node;
            $this->position = $knc+1;
        }
    }

    public function getWordType($work) {
        $word = DB::table('nouns_morf')->where('word', $work )->where('wcase','вин')->first();
        if ($word)
        {
            return 'noun';
        }
        if ((substr($work, -4) == 'ую')||(substr($work, -4) == 'юю')) {
            return 'adj';
        }
        return 'other';
    }

    public function padej ($work) {
        if (substr($work, -4) == 'ую') { return substr($work, 0,strlen($work)-4)."ая";}
        if (substr($work, -4) == 'юю') { return substr($work, 0,strlen($work)-4)."яя";}

        $word = DB::table('nouns_morf')->where('word', $work )->where('wcase','вин')->first();
        if ($word)
        {
            $parent = DB::table('nouns_morf')->where('code', $word->code_parent )->first();
            return $parent->word;
        }
    }
}
