<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\StructureVersion;

class DppTypologyPart extends Model
{

    public static function boot() {
        parent::boot();

        static::deleting(function($dtp) 
        { 
            // поднимаем position у разделов ниже
            $below_dtps = DppTypologyPart::where('ish_version_id',$dtp->ish_version_id)->where('position','>',$dtp->position);
            foreach ($below_dtps as $bd)
            {
                $bd->position = $bd->position - 1;
                $bd->save();
            }
            // отцепляем знания
            
            foreach ($dtp->get_knowledges as $knowledge)
            {
                $kn = Knowledge::find($knowledge->id);
                $knowledge->valid = false;
                $knowledge->save();
                $knowledge->sections()->detach();
                $knowledge->dtps()->detach();
            }
            $dtp->get_knowledges()->detach();

            //удаляем раздел из структуры
            $sv = $dtp->dpp->st_version_id;
            if ($sv != null)
            {
                $dtp->delete_section();
            }
        });

        static::created(function($dtp) 
        { 
            //добавляем раздел в структуру
            $sv = $dtp->dpp->st_version_id;
            if ($sv != null)
            {
                $dtp->add_section($sv);
            }
        });

        static::updated(function ($dtp)
        {
            //обновляем раздел структуры
            $sv = $dtp->dpp->st_version_id;
            if ($sv != null)
            {
                $dtp->update_section();
            }
        });

    }

    public function get_knowledges()
    {
        return $this->belongsToMany('App\Knowledge', 'knowledge_dtp', 'dtp_id', 'knowledge_id')->orderBy('knowledge_dtp.position');
    }

    public function sections()
    {
        return $this->hasMany('App\StructureSection', 'dtp_id')->orderBy('id','desc')->get();
    }

    public function knowledges()
    {
        return $this->belongsToMany('App\Knowledge', 'knowledge_dtp', 'dtp_id', 'knowledge_id')->withPivot('position')->orderBy('knowledge_dtp.position');
    }

    public function dpp()
    {
        return $this->belongsTo('App\Dpp','dpp_id');
    }

    public function add_section($sv)
    {
        $secs_count = StructureSection::where('parent_id','=',null)
        ->where('st_version_id','=',$sv)
        ->where('name','<>','Итоговая аттестация')
        ->get()->count();
        $section = new StructureSection;
        $section->name = $this->name;
        $section->lection_hours = 0;
        $section->practice_hours = 0;
        $section->self_hours = 0;
        $section->lab_hours = 0;
        $section->attestation_hours = 0;
        $section->total_hours = 0;
        $section->position = $secs_count+1;
        $section->st_version_id = $sv;
        $section->is_blocked = true;
        $section->dtp_id = $this->id;
        $section->save();
        $ia = StructureSection::where('name',"Итоговая аттестация")->where('st_version_id',$sv)->get()->first();
        $ia->position = $secs_count+2;
        
        $ia->save();
    }

    public function delete_section()
    {
        $parent_section = StructureSection::where('parent_id',null)->where('dtp_id',$this->id)->get()->first();
        if ($parent_section)
        {
            StructureSection::destroy($parent_section->id);
        }
    }

    public function update_section()
    {
        $section = StructureSection::where('parent_id',null)->where('dtp_id',$this->id)->get()->first();
        $section->position = $this->position;
        $section->name = $this->name;
        $section->save();
    }

    public function updateName($name)
    {
        $this->name = $name;
        $this->save();
    }
}
