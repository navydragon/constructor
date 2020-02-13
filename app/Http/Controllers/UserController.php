<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use App\DppUserRole;
use Carbon\Carbon;
class UserController extends Controller
{
    public function get_all_users()
    {
        $users = User::all();
        foreach ($users as $user)
        {
            $user->registered_at = $user->created_at->format('d.m.Y');
            $user->text = $user->fullname;
            $user->value = $user->id;
        }
        return $users;
    }

    public function get_all_roles()
    {
        $roles = Role::all();
        foreach ($roles as $role)
        {
            $role->text = $role->name;
            $role->value = $role->id;
        }
        return $roles;
    }

    public function add_dpp_user_role(Request $request)
    {
        $dur = DppUserRole::all()->where('user_id',$request->user)
        ->where('dpp_id',$request->dpp)
        ->where('role_id',$request->role);
        if ($dur->count() > 0)
        {
            return "exists";
        }
        $dur = new DppUserRole;
        $dur->user_id = $request->user;
        $dur->dpp_id = $request->dpp;
        $dur->role_id = $request->role;
        $dur->save();
        $u = User::find($request->user);
        $dur->fullname = $u->fullname;
        $r = Role::find($request->role);
        $dur->rolename = $r->name;
        return $dur;
    }
    public function delete_dpp_user_role(Request $request)
    {
        DppUserRole::destroy($request->id);
    }
}
