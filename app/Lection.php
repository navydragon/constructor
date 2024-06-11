<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Mto;
class Lection extends Model
{
    

    public static function boot() {
        parent::boot();
        static::deleting(function($lection) 
        { 
            foreach ($lection->additional_files as $file)
            {
                $file->delete();
            }
        });

    }

    protected $guarded = [];

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

    public function additional_files()
    {
        return $this->hasMany('App\AdditionalFile', 'lection_id')->orderBy('position');
    }

    public function by_superviser()
    {
        return $this->belongsTo('App\User','superviser_id');
    }
    
    public function by_normocontroller()
    {
        return $this->belongsTo('App\User','normocontroller_id');
    }

}
