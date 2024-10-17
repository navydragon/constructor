<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StructureSection extends Model
{
    protected $appends = array('have_practice');

    public static function boot() {
        parent::boot();

        static::deleting(function($section)
        {
            //отцепляем ЗУНы
            $section->knowledges()->detach();
            $section->abilities()->detach();
            $section->skills()->detach();
            //удаляем контент
            //$section->contents()->delete();
            foreach ($section->contents as $content)
            {
                $content->delete();
            }
            // поднимаем section ниже
            $section->moveUpBelowSections();
            //удаляем вложенные темы
            foreach ($section->themes as $theme)
            {
                StructureSection::destroy($theme->id);
            }
        });

        static::updated(function($section)
        {
            if ($section->parent_id == null)
            {
                if ($section->dtp != null)
                {
                    $section->dtp->updateName($section->name);
                }
            }
        });
    }

    public function st_version()
    {
        return $this->belongsTo('App\StructureVersion', 'st_version_id');
    }

    public function knowledges()
    {
        return $this->belongsToMany('App\Knowledge', 'knowledge_section', 'section_id', 'knowledge_id');
    }

    public function dtp()
    {
        return $this->belongsTo('App\DppTypologyPart', 'dtp_id');
    }

    public function getHavePracticeAttribute()
    {
        $res = false;
        foreach ($this->knowledges as $knowledge)
        {
            if ($knowledge->ability_id != null)
            {
                $res = true;
            }
        }
        return $res;
    }

    public function skills()
    {
        return $this->belongsToMany('App\Skill', 'skill_section', 'section_id', 'skill_id');
    }

    public function abilities()
    {
        return $this->belongsToMany('App\Ability', 'ability_section', 'section_id', 'ability_id');
    }

    public function themes()
    {
        return $this->hasMany('App\StructureSection','parent_id')->orderBy('position');
    }

    public function contents()
    {
        return $this->hasMany('App\Lection','section_id');
    }
    public function parent()
    {
        return $this->belongsTo('App\StructureSection','parent_id');
    }

    public function lections()
    {
        return $this->hasMany('App\Lection','section_id');
    }

    public function delete_theme()
    {
        $theme = StructureSection::find($this->id);
        // $theme->knowledges()->detach();
        // $theme->abilities()->detach();
        // $theme->skills()->detach();

        // $theme->lections()->delete();


        // $below_themes = StructureSection::where('position','>',$theme->position)->where('parent_id','=',$theme->parent_id)->get();
        // foreach ($below_themes as $bt)
        // {
        //     $bt->position = $bt->position - 1;
        //     $bt->save();
        // }
        // if ($theme->parent_id)
        // {
        //     $section = StructureSection::find($theme->parent_id);
        //     $section->lection_hours -= $theme->lection_hours;
        //     $section->practice_hours -= $theme->practice_hours;
        //     $section->self_hours -= $theme->self_hours;
        //     $section->total_hours -= $theme->total_hours;
        //     $section->lab_hours -= $theme->lab_hours;
        //     $section->attestation_hours -= $theme->attestation_hours;
        //     $section->save();
        // }
        StructureSection::destroy($this->id);
        return response()->json(['message'=>'success'],200);
    }

    public function moveUpBelowSections()
    {
        $below_sections = StructureSection::where('parent_id',$this->parent_id)->where('position','>',$this->position)
        ->where('st_version_id',$this->st_version_id)->get();
        foreach ($below_sections as $bs)
        {
            $bs->position = $bs->position - 1;
            $bs->save();
        }
    }

    public function recount_section()
    {
        $section = StructureSection::find($this->id);
        if ($section->themes()->count() > 0) {
            $section->lection_hours = 0; $section->lection_hours_o = 0; $section->lection_hours_z = 0;
            $section->practice_hours = 0; $section->practice_hours_o = 0; $section->practice_hours_z = 0;
            # $section->consult_hours = 0;  $section->consult_hours_o = 0; $section->consult_hours_z = 0;
            $section->self_hours = 0; $section->lab_hours = 0;
            $section->total_hours = 0;

            foreach ($section->themes as $theme)
            {
                $section->lection_hours += $theme->lection_hours;
                $section->lection_hours_o += $theme->lection_hours_o;
                $section->lection_hours_z += $theme->lection_hours_z;

                $section->practice_hours += $theme->practice_hours;
                $section->practice_hours_o += $theme->practice_hours_o;
                $section->practice_hours_z += $theme->practice_hours_z;

                $section->consult_hours += $theme->consult_hours;
//            $section->consult_hours_o += $theme->consult_hours_o;
//            $section->consult_hours_z += $theme->consult_hours_z;

                $section->lab_hours += $theme->lab_hours;

                $section->self_hours += $theme->self_hours;
                # $section->total_hours = $theme->total_hours;

                // $section->attestation_hours += $theme->attestation_hours;
            }
            $section->total_hours += $section->lection_hours_o;
            $section->total_hours += $section->lection_hours_z;
            $section->total_hours += $section->practice_hours_o;
            $section->total_hours += $section->practice_hours_z;
            $section->total_hours += $section->consult_hours_o;
            $section->total_hours += $section->consult_hours_z;

            $section->total_hours += $section->attestation_hours;
//        $section->total_hours += $section->consult_hours;
//        $section->total_hours += $section->practice_hours;
//        $section->total_hours += $section->lection_hours;
            $section->save();
        }

        return $section;
    }
}
