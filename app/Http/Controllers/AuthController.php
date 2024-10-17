<?php

namespace App\Http\Controllers;

use Auth;
use JWTAuth;
use App\User;
use App\Http\Requests\RegisterFormRequest;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(RegisterFormRequest $request)
    {
        $user = new User;
        $user->email = $request->email;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->middlename = $request->middlename ?? "";
        $user->phone = $request->phone;
        $user->fullname = $request->lastname." ".$request->firstname." ".$request->middlename;
        $user->password = bcrypt($request->password);
        $user->save();

        return response([
            'status' => 'success',
            'data' => $user
        ], 200);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if ( ! $token = JWTAuth::attempt($credentials)) {
            return response([
                'status' => 'error',
                'error' => 'invalid.credentials',
                'msg' => 'Invalid Credentials.'
            ], 400);
        }

        $user = JWTAuth::user();

        // Проверяем атрибут is_active
        if (!$user->is_active) {
            return response([
                'status' => 'error',
                'error' => 'inactive.user',
                'msg' => 'User is not active.'
            ], 403);
        }

        return response([
            'status' => 'success',
            'token' => $token
        ])
        ->header('Authorization', $token);
    }

    public function user(Request $request)
    {
        $user = User::find(Auth::user()->id);
        if ($user->is_active == false) {
            return response([
                'status' => 'not active',
                'data' => false
            ]);
        }
        $user->middlename = $user->middlename ?? "";
        $user->rights = $user->get_rights->shortname;
        return response([
            'status' => 'success',
            'data' => $user
        ]);
    }

    public function refresh()
    {
        return response([
            'status' => 'success'
        ]);
    }

    public function logout()
    {
        JWTAuth::invalidate();

        return response([
            'status' => 'success',
            'msg' => 'Logged out Successfully.'
        ], 200);
    }

}
