<?php
namespace App;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Http\Traits\TryDelete;
use App\Dpp;
use App\DppUserRole;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use TryDelete;
    // Rest omitted for brevity

    public static function boot() {
        parent::boot();
        static::created(function($user)
        {
            $user->createTestDpp();
        });
    }


    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function get_rights()
    {
        return $this->belongsTo('App\Right','right_id');
    }

    public function is_admin()
    {
        return $this->get_rights->id == 1;
    }

    public function created_dpps()
    {
        return $this->hasMany('App\Dpp','author_id');
    }

    public function company()
    {
        return $this->belongsTo('App\Company','company_id');
    }

    public function createTestDpp()
    {
        $dpp = new Dpp;
        $dpp->name = "Тестовая ДПП ".$this->fullname;
        $dpp->total_hours = 1;
        $dpp->dpp_type_id = $dpp->setType(1);
        $dpp->author_id = auth()->user()->id;
        $dpp->abbreveation = $dpp->setAbbreveation("Тестовая ДПП ".$this->fullname);
        $dpp->status_id = 1;
        $dpp->save();

        $dur = new DppUserRole;
        $dur->user_id = $this->id;
        $dur->dpp_id = $dpp->id;
        $dur->role_id = 1;
        $dur->save();
    }
}
