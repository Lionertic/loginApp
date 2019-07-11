<?php

namespace App\Http\Controllers\Api\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request){

//        $request->validate([
//            'name' => 'required|min:2|max:50',
//            'email' => 'required|email|unique:users',
//            'password' => 'required|min:6',
//            'confirm_password' => 'required|min:6|max:20|same:password',
//        ]);

        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);

        $user->save();
        $accessToken = $user->createToken('authToken');

        return response(['user'=>$user,'accessToken'=>$accessToken]);

    }

    public function login(Request $request){

        $loginData = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if(!Auth::attempt($loginData)){
            return 'wrong user';
        }

        $accessToken = Auth::user()->createToken('authToken');

        return response(['user'=>Auth::user(),'accessToken'=>$accessToken]);
    }

    public function logout(Request $request)
    {
        dd($request->user()->token()->revoke());
    }
}
