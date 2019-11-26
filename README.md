# Exposé
Guide for my Laravel Applications.

# Passport
Laravel already makes it easy to perform authentication via traditional login forms, but what about APIs? APIs typically use tokens to authenticate users and do not maintain session state between requests. Laravel makes API authentication a breeze using Laravel Passport, which provides a full OAuth2 server implementation for your Laravel application in a matter of minutes.

## Installation
1. Install the package
`composer require laravel/passport`

2. Migrate - Passport migrations will create the tables your application needs to store clients and access tokens
`php artisan migrate`

3. Create the encryption keys needed to generate secure access tokens. In addition, the command will create "personal access" and "password grant" clients which will be used to generate access tokens
`php artisan passport:install`

4. Add the `Laravel\Passport\HasApiTokens` trait to your `App\Models\User` model. This trait will provide a few helper methods to your model which allow you to inspect the authenticated user's token and scopes.
```php
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
}
```

5. Call the `Passport::routes` method within the `boot` method of your `AuthServiceProvider.php`
```php
use Laravel\Passport\Passport;
.
..
...
public function boot()
{
    $this->registerPolicies();

    Passport::routes();
}
```

6. Finally, in your `config/auth.php` configuration file, you should set the driver option of the api authentication guard to passport. 
```php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],

    'api' => [
        'driver' => 'passport',
        'provider' => 'users',
    ],
],
```

### Migration Customization
If you are not going to use Passport's default migrations, you should call the `Passport::ignoreMigrations` method in the register method of your `AppServiceProvider.php`. You may export the default migrations using `php artisan vendor:publish --tag=passport-migrations`.

By default, Passport uses an integer column to store the `user_id`. If your application uses a different column type to identify users (for example: UUIDs), you should modify the default Passport migrations after publishing them.

## Configuration
### Token Lifetimes
By default, Passport issues long-lived access tokens that expire after one year. If you would like to configure a longer / shorter token lifetime.

If you wish to change that:
> These methods should be called from the `boot` method of your `AuthServiceProvider.php`
```php
Passport::tokensExpireIn(now()->addDays(15));

Passport::refreshTokensExpireIn(now()->addDays(30));

Passport::personalAccessTokensExpireIn(now()->addMonths(6));
```

## Personal Client Token
Sometimes, your users may want to issue access tokens to themselves without going through the typical authorization code redirect flow. Allowing users to issue tokens to themselves via your application's UI can be useful for allowing users to experiment with your API or may serve as a simpler approach to issuing access tokens in general.

### Creating A Personal Access Client
In `AuthServiceProvider.php`,
```php
Passport::personalAccessClientId('client-id');
```

### Managing Personal Access Tokens
Once you have created a personal access client, you may issue tokens for a given user using the createToken method on the User model instance. The createToken method accepts the name of the token as its first argument and an optional array of scopes as its second argument:
```php
$user = App\User::find(1);

// Creating a token without scopes...
$token = $user->createToken('Token Name')->accessToken;

// Creating a token with scopes...
$token = $user->createToken('My Token', ['place-orders'])->accessToken;
```