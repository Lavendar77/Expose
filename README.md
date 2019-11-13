# Exposé
Guide for my Laravel Applications.

## UUID Set-up For Models
UUID stands for **Universal Unique Identifier**. It's a 128-bit number used to uniquely identify some object or in our case, a record in our database.

### Prepare your migration
Change the default increments on the `id` column to `uuid`
```php
$table->uuid('id')->primary();
```

### Create a trait for easy use
Assuming that you already checked the [Laravel Traits](https://github.com/Lavendar77/Expose/tree/laravel-traits) branch
```
php artisan make:trait Models\UUID
```

### Configuring your trait
```php
namespace App\Traits\Models;

use Illuminate\Support\Str;

trait UUID
{
	/*
	 * Boot function
	 *
	 * Hook into our model and listen for any Eloquent events
	*/
    protected static function bootUUID()
    {
        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    /*
	 * Disable autoincrementing on model
    */
    public function getIncrementing()
    {
        return false;
    }

    /*
	 * IDs on the table should be stored as strings.
    */
    public function getKeyType()
    {
        return 'string';
    }
}
```
**Note: Make sure the boot.... has the same name as the class name `protected static function bootUUID()`**
> `trait Name`
>> `protected static function bootName()`

### Using our Trait
In the model in concern:
```php
use App\Traits\Models\UUID;
.
..
...
use UUID;
```

### Testing with Foreign Keys
1. Create another model **UserLanguage** and its migration
```
php artisan make:model Models\UserLanguage -m
```
2. Add the foreign key
```php
$table->uuid('user_id');
$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
```
3. Add your trait to the model
4. Add the relationship schema to both models
5. Test
