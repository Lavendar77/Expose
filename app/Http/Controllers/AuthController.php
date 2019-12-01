<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use JWTAuth;
use App\Models\User;
use Illuminate\Support\MessageBag;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:8'
        ]);

    	$credentials = $request->only('email', 'password');
        if (! $token = JWTAuth::attempt($credentials)) {
            $errors = new MessageBag([
                'email' => [
                    trans('auth.failed')
                ]
            ]);

            return response()->json([
                'errors' => $errors
            ])->setStatusCode(401);
        }

        return $this->respondWithToken($token);
    }

    public function register(Request $request)
    {
    	$request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8|confirmed'
        ]);

        $credentials = $request->only('email', 'password');

        $request->merge(['password' => \Hash::make($request->password)]);
    	$user = User::create($request->all());

        if (! $token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::guard('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth('api')->factory()->getTTL() * 60,
        ]);
    }
}
