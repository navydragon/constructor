<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lection extends Model
{
    public function section()
    {
        return $this->belongsTo('App\StructureSection','section_id');
    }

    public function parts()
    {
        return $this->hasMany('App\Content','lection_id')->orderBy('position');
    }

    public function nsis()
    {
        return $this->belongsToMany('App\Nsi', 'lection_nsis', 'lection_id', 'nsi_id');
    }

}
