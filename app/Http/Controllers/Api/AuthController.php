<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\RegisterResource;
use App\Http\Services\AuthService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends BaseController
{

    function __construct()
    {
        $this->service = new AuthService;
        $this->user = new User();
    }

    function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $result = $this->service->register($request);
        return $this->sendResponse($result, 'User register successfully');
    }

    function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        if (Auth::attempt($request->all())) {
           $result = $this->service->login($request->all());

            return $this->sendResponse($result, 'Successfully to login');
        } else {
            return $this->sendError('Unauthorized', ['error' => 'Unauthorized']);
        }
    }

    function test()
    {
        return response()->json([
            'message' => 'Passed !!!',
            'error' => 0,
            'success' => true
        ]);
    }

    function google()
    {
        return Socialite::driver('google')->redirect();
    }

    function googleCallback()
    {
        if (Auth::check()) {
            return redirect('login');
        }

        $oauthUser = Socialite::driver('google')->user();
        $user = $this->user->where('google_id', $oauthUser->id)->first();

        if ($user) {
            Auth::loginUsingId($user->id);
            return redirect('api/google/passed');
        } else {
            $createUser = $this->user->create([
                'name' => $oauthUser->name,
                'email' => $oauthUser->email,
                'google_id' => $oauthUser->id,
                'password' => bcrypt('acception')
            ]);

            Auth::login($createUser);
            return redirect('api/google/passed');
        }
    }
}
