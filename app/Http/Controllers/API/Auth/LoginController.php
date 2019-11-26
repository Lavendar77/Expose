<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
    	$authenticate = Auth::attempt([
    		'email' => $request->email,
    		'password' => $request->password
    	]);

    	abort_unless($authenticate, 401);

    	$user = Auth::user();
		$token = $user->createToken('token')->accessToken;
    	
    	return response()->json([
    		'authenticate' => $authenticate,
    		'token' => $token
    	]);

    }
}
