# Exposé
Guide for my Laravel Applications.

## JWT For Laravel 6^
JSON Web Token - Basically, like Laravel Passport, but minimal.
> [Documentation](https://jwt-auth.readthedocs.io/en/develop/)

### Installation
```bash
composer require tymon/jwt-auth:^1.0.0-rc.5
```

### Configuration
1. Generate the secret key
```bash
php artisan jwt:secret
```
This will create a `JWT_SECRET` in the `.env` file

2. Add to the `User` model
```php
use Tymon\JWTAuth\Contracts\JWTSubject;
.
..
...
class User extends Authenticatable implements JWTSubject {
	/**
	 * Get the identifier that will be stored in the subject claim of the JWT.
	 *
	 * @return mixed
	 */
	public function getJWTIdentifier()
	{
	    return $this->getKey();
	}

	/**
	 * Return a key value array, containing any custom claims to be added to the JWT.
	 *
	 * @return array
	 */
	public function getJWTCustomClaims()
	{
	    return [];
	}
}
```

3. Configure Auth Guard
Inside the `config/auth.php` file,
```php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],

    'api' => [
        'driver' => 'jwt',
        'provider' => 'users',
        'hash' => false,
    ],
],
```

4. In your `LoginController.php`
```php
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

public function login(Request $request)
{
	if (! $token = Auth::attempt($credentials)) {
		// return 401
	}

	return $this->respondWithToken($token);
}
```

# Incomplete yet! Hope to finish soon