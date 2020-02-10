<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Carbon\Carbon;
class UserController extends Controller
{
    public function get_all_users()
    {
        $users = User::all();
        foreach ($users as $user)
        {
            $user->registered_at = $user->created_at->format('d.m.Y');
        }
        return $users;
    }
}
