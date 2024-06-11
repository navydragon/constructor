<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IshVersion extends Model
{
    function prof_levels () {
        return $this->belongsToMany('App\ProfLevel', 'dpp_prof_levels', 'ish_version_id', 'prof_level_id');
    }

    function ektses () {
        return $this->belongsToMany('App\Ekts', 'dpp_ekts', 'ish_version_id', 'ekts_id');
    }

    function ekses () {
        return $this->belongsToMany('App\Eks', 'dpp_eks', 'ish_version_id', 'eks_id');
    }

    function world_skills () {
        return $this->belongsToMany('App\WorldSkills', 'dpp_world_skills', 'ish_version_id', 'world_skill_id');
    }

    public function nsis()
    {
        return $this->hasMany('App\Nsi','ish_version_id');
    }

    public function typology_parts()
    {
        return $this->hasMany('App\DppTypologyPart','ish_version_id')->orderBy('position','asc');
    }

    function prof_standarts () 
    {
        return $this->belongsToMany('App\ProfStandart', 'dpp_prof_standarts', 'ish_version_id', 'prof_standart_id');
    }
    
    function corporate_requirements()
    {
        return $this->belongsToMany('App\CorporateRequirement', 'dpp_corporate_requirements', 'ish_version_id', 'corporate_requirement_id');
    }

    function fgoses () 
    {
        return $this->belongsToMany('App\Fgos', 'dpp_fgoses', 'ish_version_id', 'fgos_id');
    }

    function dolg_kvals () 
    {
        return $this->belongsToMany('App\DolgKval', 'dpp_dolg_kvals', 'ish_version_id', 'dolg_kval_id');
    }
 
    function direction () {
        return $this->belongsTo('App\ProgramDirection','direction_id');
    }

    public function dpp()
    {
        return $this->belongsTo('App\Dpp','dpp_id');
    }

    public function professional_field()
    {
        return $this->belongsTo('App\ProfessionalField','professional_field_id');
    }

    public function qualification_requirements()
    {
        return $this->hasMany('App\QualificationRequirement','ish_version_id')->orderBy('position');
    }

    public function professional_objects()
    {
        return $this->hasMany('App\ProfessionalObject','ish_version_id')->orderBy('position');
    }

    public function digital_sphere()
    {
        return $this->belongsTo('App\DigitalSphere','digital_sphere_id');
    }
    
}
