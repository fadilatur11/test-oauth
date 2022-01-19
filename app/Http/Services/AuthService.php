<?php

namespace  App\Http\Services;

use App\User;
use Auth;

Class AuthService
{
    function __construct()
    {
        $this->user = new User();
    }
    function register($request)
    {
        $user = $this->user->create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password'])
        ]);

        $result['token'] = $user->createToken('MyApp')->accessToken;
        $result['name'] = $user->name;

        return $result;
    }

    function login($request)
    {
        $user = Auth::user();
        $result['token'] = $user->createToken('MyApp')->accessToken;
        $result['name'] = $user->name;

        return $result;
    }
}
