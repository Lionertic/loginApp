<?php

namespace App\Http\Controllers\Api\Auth;

use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\MustVerifyEmail;

class AuthController extends Controller
{
    use MustVerifyEmail;
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
        $user->password = Hash::make($request->password);

        $user->save();
        $user->sendApiEmailVerificationNotification();
//        $accessToken = $user->createToken('authToken');

        return response(['user'=>$user,'accessToken'=>'']);

    }

    public function login(Request $request){

        $loginData = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if(!Auth::attempt($loginData)){
            return 'wrong user';
        }

        if(!Auth::user()->email_verified_at){
            return response(['user'=>Auth::user(),'accessToken'=>'']);
        }
        $accessToken = Auth::user()->createToken('authToken');

        return response(['user'=>Auth::user(),'accessToken'=>$accessToken]);
    }

    public function logout(Request $request)
    {
        dd($request->user()->token()->revoke());
    }
}
