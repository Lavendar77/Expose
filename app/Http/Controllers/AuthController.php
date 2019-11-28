<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;

use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:8'
        ]);

    	$authenticate = Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ], $request->remember);

    	if (!$authenticate) {
            $errors = new MessageBag([
                'email' => [
                    trans('auth.failed')
                ]
            ]);

            return response()->json([
                'errors' => $errors
            ])->setStatusCode(401);
        }
    	
    	return response()->json([
    		'token' => $this->getToken(),
    	]);
    }

    public function register(Request $request)
    {
    	$request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8|confirmed'
        ]);

        $request->merge(['password' => \Hash::make($request->password)]);

    	$user = User::create($request->all());

        Auth::login($user);

    	return response()->json([
            'token' => $this->getToken()
        ]); 
    }

    public function getToken()
    {
        $user = Auth::user();

        return $user->createToken(Str::random(40));
    }
}
