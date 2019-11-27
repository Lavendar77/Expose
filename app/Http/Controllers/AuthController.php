<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;

use Illuminate\Support\MessageBag;

class AuthController extends Controller
{
    public function login(Request $request)
    {
    	$authenticate = Auth::attempt([
    		'email' => $request->email,
    		'password' => $request->password
    	], $request->filled('remember'));

    	if (!$authenticate) {
            $errors = new MessageBag([
                'email' => [
                    'These credentials do not match our records.'
                ]
            ]);

            return response()->json([
                'errors' => $errors
            ])->setStatusCode(401);
        }

    	$user = Auth::user();
		$token = $user->createToken('token')->accessToken;
    	
    	return response()->json([
    		'authenticate' => $authenticate,
    		'token' => $token
    	]);
    }

    public function register(Request $request)
    {
    	$validated = $request->validate([
    		'name' => 'required|string|max:255',
    		'email' => 'required|string|email|unique:users',
    		'password' => 'required|string|min:8|confirmed'
    	]);

    	$user = new User();
    	$user->name = $request->name;
    	$user->email = $request->email;
    	$user->password = \Hash::make($request->password);
    	$user->save();

    	return $this->login($request);
    }
}
