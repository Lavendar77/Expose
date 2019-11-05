# Expose
Guide for my Laravel Applications.

---

## Organizing your Laravel Models
Personally, I like my code structure with as many folders as I can use. Of course, it can become complex as time goes on, but I like to keep things as neat as possible.

### Updating the `User` model
- Move the `/app/User.php` file into a new folder (Models) as **`app/Models/User.php`**.
- Modify the namespace of the file
: From `namespace App` to `namespace App\Models`
- Update all the files that reference the old namespace (App\User)
	1. `/config/auth.php` 

		```
			'providers' => [
			    'users' => [
			        'driver' => 'eloquent',
			        'model' => App\Models\User::class,
			    ],
			],
		```

	2.	Change `use App\User` to `use App\Models\User` in;
		- `database/factories/UserFactory.php`
		- `app/Http/Controllers/Auth/RegisterController.php`
			<br>If you are concerned about the comments in this file too, you might as well just edit that too
			```
				/**
			     * Create a new user instance after a valid registration.
			     *
			     * @param  array  $data
			     * @return \App\Models\User
			     */
			```

### Composer Refresh
Refresh composer autoload for new model classes

- Modify `composer.json`
```
	"autoload": {
        "psr-4": {
            "App\\": "app/"
        },
        "classmap": [
            "database/seeds",
            "database/factories",
            "app/Models"
        ]
    },
```
- Run `composer dumpautoload`


### Generating models afterwards 
Whenever you want to register new models, you have to use `php artisan make:model Models\ModelName`.

I have not figured out a permanent solution yet, but I will keep searching :wink: 
