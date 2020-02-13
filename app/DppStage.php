<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DppStage extends Model
{
    public function type()
    {
        return $this->belongsTo('App\StageType','stage_type_id');
    }
    
    public function status()
    {
        return $this->belongsTo('App\StageStatus','stage_status_id');
    }

    public function dpp()
    {
        return $this->belongsTo('App\Dpp','dpp_id');
    }
}
