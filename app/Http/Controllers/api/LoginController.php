<?php

namespace App\Http\Controllers\api;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
    Auth,
    Hash,
    Validator
};

class LoginController extends Controller
{
    /**
     * Registers a newly created user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|unique:users',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'failed',
                'message' => $validator->errors()
            ]);
        } else {
            try {
                $user = new User;
                $user->name = $request->name;
                $user->email = $request->email;
                $user->password = Hash::make($request->password);
                $user->save();

                return response([
                    'status' => 'success',
                    'message' => 'user successfully registered'
                ]);
            } catch (\Exception $e) {
                return response([
                    'status' => 'failed',
                    'message' => $e
                ]);
            }
        }
    }

    /**
     * Login request to get access token
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $login = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        if (!Auth::attempt($login)) {
            return response([
                'message' => 'Invalid login credentials'
            ]);
        } else {
            $accessToken = Auth::user()
                ->createToken('authToken')
                ->accessToken;

            return response([
                'access_token' => $accessToken
            ]);
        }
    }
}
