<?php

namespace App\Http\Controllers;

use App\User;
use App\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    
    public function csrf() {
        $encrypter = app('Illuminate\Encryption\Encrypter');
        $encrypted_token = $encrypter->encrypt(csrf_token());
        return csrf_token();
    }

    public function register(Request $request)
    {
        $user = new User;
        $user->name = $request->name;
        $user->save();
        $passwords = array();
        for($i = 0 ; $i < 20; $i++ ) {
            $password = new Password;
            $str = Str::random(10);
            array_push($passwords, $str);
            $password->pass = Hash::make($str);
            $user->passwords()->save($password);
        }

        return $passwords;
    }

    public function login(Request $request)
    {
        $user = User::where('name', '=', $request->name)->firstOrFail();
        $user->load('passwords');
        $p = 0;
        foreach($user->passwords as $password)
        {
            $p += Hash::check($request->password, $password->pass) ? 1 : 0;
        }

        return $p > 0 ? User::where('name', '=', $request->name)->firstOrFail() : null;
    }
}
