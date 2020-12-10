<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use App\DppUserRole;
use Carbon\Carbon;
use Mail;
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

    public function send_reg_data(Request $request)
    {
        $user = User::find($request->id);
        $data = array('name'=>"ХРЮН МОРЖОВ",'email'=>'hriun@hr.ru','password'=>'kek');
        Mail::send('emails.send_reg', $data, function($message) {
            $message->to('ief07@bk.ru', 'Tutorials Point')->subject
               ('Регистрация в конструкторе ДПП');
            $message->from('no-reply@edu.emiit.ru','Робот-почтальон конструктора ДПП');
         });
         echo "HTML Email Sent. Check your inbox.";
    }

    public function check_email(Request $request)
    {
        $email = $request->email;
        $users = User::where('email',$email)->get()->count();
        return $users;
    }

    public function add_user(Request $request)
    {
        $data = $request->user_data["user_data"];
        $password = rand(100000, 999999);
        $user = new User;
        $user->email = $data["email"];
        $user->firstname = $data["firstname"];
        $user->lastname = $data["lastname"];
        $user->middlename = $data["middlename"];
        $user->phone = $data["phone"];
        $user->fullname = $data["lastname"]." ".$data["firstname"]." ".$data["middlename"];
        $user->password = bcrypt($password);
        $user->save();

        $data = array('name'=>$user->fullname,'email'=>$user->email,'password'=>$password);
        Mail::send('emails.send_reg', $data, function($message) use ($user) {
            $message->to($user->email,$user->fullname)->subject
               ('Регистрация в конструкторе ДПП');
            $message->from('no-reply@edu.emiit.ru','Робот-почтальон конструктора ДПП');
         });
        return json_encode($user);
    }

    public function discard_password(Request $request)
    {
        $user = User::find($request->user);
        $password = 111111;
        $user->password = bcrypt($password);
        $user->save();
        $data = array('name'=>$user->fullname,'email'=>$user->email,'password'=>$password);
        // Mail::send('emails.discard_password', $data, function($message) use ($user) {
        //     $message->to($user->email,$user->fullname)->subject
        //        ('Конструктор ДПП: Ваш пароль был сброшен');
        //     $message->from('no-reply@edu.emiit.ru','Робот-почтальон конструктора ДПП');
        //  });
    }
}
