<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Knowledge extends Model
{
    use SoftDeletes;
    
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

    public function sections()
    {
        return $this->belongsToMany('App\StructureSection', 'knowledge_section', 'knowledge_id', 'section_id');
    }
}
