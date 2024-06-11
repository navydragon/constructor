<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DppUserRole extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }

    public function role()
    {
        return $this->belongsTo('App\Role','role_id');
    }

    public function dpp()
    {
        return $this->belongsTo('App\Dpp','dpp_id');
    }
}
