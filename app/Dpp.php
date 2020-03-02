<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Role;
class Dpp extends Model
{
    public function type()
    {
        return $this->belongsTo('App\DppType','dpp_type_id');
    }
    public function status()
    {
        return $this->belongsTo('App\DppStatus','status_id');
    }
    public function participants()
    {
        $participants =  $this->hasMany('App\DppUserRole','dpp_id');
        return $participants;
    }
    
    public function stages()
    {
        return $this->hasMany('App\DppStage','dpp_id');
    }
    public function current_stage()
    {
        return $this->belongsTo('App\DppStage','current_stage_id');
    }

    public function zun_versions()
    {
        return $this->hasMany('App\ZunVersion','dpp_id');
    }

    public function ish_versions()
    {
        return $this->hasMany('App\IshVersion','dpp_id');
    }

    public function om_versions()
    {
        return $this->hasMany('App\OmVersion','dpp_id');
    }

    public function knowledges()
    {
        return $this->hasMany('App\Knowledge','dpp_id');
    }
}
