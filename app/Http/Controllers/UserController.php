<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use App\DppUserRole;
use Carbon\Carbon;
use Mail;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\Role as RoleResource;
use App\Http\Resources\UserCollection;
use App\Http\Resources\RoleCollection;
class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return new UserCollection($users);
    }
    public function show(User $user)
    {
        return new UserResource($user);
    }
   
    public function store (Request $request)
    {
        $data = $request->user;
        $already = User::where('email', '=', $data["email"])->get();
        if (count($already) >0)
        {
                return response([
                    'status' => 'error',
                    'msg' => 'Указанная почта уже зарегистрирована для другого пользователя'
                ], 409);
        }
        $user = new User;
        $password = rand(100000, 999999);
        $user->email = $data["email"];
        $user->firstname = $data["firstname"];
        $user->lastname = $data["lastname"];
        $user->middlename = $data["middlename"];
        $user->is_active = 1;
        $user->phone = $data["phone"];
        $user->fullname = $data["lastname"]." ".$data["firstname"]." ".$data["middlename"];
        $user->password = bcrypt($password);
        $user->save();
        $data = array('name'=>$user->fullname,'email'=>$user->email,'password'=>$password);
       Mail::send('emails.send_reg', $data, function($message) use ($user) {
           $message->to($user->email,$user->fullname)->subject
              ('Регистрация в конструкторе ДПП');
           $message->from('admin@emiit.ru','Робот-почтальон конструктора ДПП');
        });
        return new UserResource($user);
    }

    public function update(User $user, Request $request)
    {
        $data = $request->user;
        $already = User::where('email', '=', $data["email"])->get();
        if (count($already) >0)
        {
            $already = $already->first();
            if ($already->id != $data["id"])
            {
                return response([
                    'status' => 'error',
                    'msg' => 'Указанная почта уже зарегистрирована для другого пользователя'
                ], 409);
            }
        }
        $user->firstname = $data["firstname"];
        $user->lastname = $data["lastname"];
        $user->middlename = $data["middlename"];
        $user->fullname = $data["fullname"];
        $user->phone = $data["phone"];
        $user->email = $data["email"];
        $user->is_active = $data["isActive"];
        $user->save();
        return new UserResource($user);
    }

    public function destroy(User $user)
    {
        if ($user->tryDelete()) 
        {
            return $user->id;
        }else{
            return response()->json(['message'=>'Невозможно удалить данный объект, так как он связан с другими объектами системы.'],409);
        }
    }

    public function update_by_user(Request $request)
    {
        $user = User::findOrFail($request->input('userInfoUpdate.id'));
        $user->email = $request->input('userInfoUpdate.email');
        $user->firstname = $request->input('userInfoUpdate.firstname');
        $user->lastname = $request->input('userInfoUpdate.lastname');
        $user->middlename = $request->input('userInfoUpdate.middlename');
        $user->phone = $request->input('userInfoUpdate.phone');
        $user->fullname = $request->input('userInfoUpdate.lastname')." ".$request->input('userInfoUpdate.firstname')." ".$request->input('userInfoUpdate.middlename');
        $user->save();
        return response([
            'status' => 'OK',
        ], 200); 
        return new UserResource($user);
    }

    public function roles_index()
    {
        $roles = Role::all();
        return new RoleCollection($roles);

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

    
    public function reset_password(User $user, Request $request)
    {
        if (auth()->user()->right_id == 1)
        {
            $password = 111111;
            $user->password = bcrypt($password);
            $user->save();
            $data = array('name'=>$user->fullname,'email'=>$user->email,'password'=>$password);
            Mail::send('emails.discard_password', $data, function($message) use ($user) {
                $message->to($user->email,$user->fullname)->subject
                ('Конструктор ДПП: Ваш пароль был сброшен');
                $message->from('admin@emiit.ru','Робот-почтальон конструктора ДПП');
            });
            return response([
                'status' => 'OK',
            ], 200); 
        }else{
            return response([
                'status' => 'error',
                'message' => 'Недостаточно прав доступа'
            ], 403); 
        }
    }

    public function change_password(User $user, Request $request)
    {
        $user->password = bcrypt($request->input('password'));
        $user->save();
        return response([
            'status' => 'OK',
        ], 200); 
    }
}
