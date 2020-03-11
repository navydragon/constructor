<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DppTypologyPart extends Model
{
    public function get_knowledges()
    {
        return $this->belongsToMany('App\Knowledge', 'knowledge_dtp', 'dtp_id', 'knowledge_id');
    }
}
