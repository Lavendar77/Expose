# Expose
Guide for my Laravel Applications.

## Database
Let us start with database configurations.

- Create the database in your phpMyAdmin
- Open `.env` file
```
DB_DATABASE=databaseName
DB_USERNAME=root
DB_PASSWORD=
```
In my case, `DB_DATABASE=expose`

### Aid Uninterrupted Migrations
In `app/Providers/AppServiceProviders/php`
```php
use Illuminate\Support\Facades\Schema;
.
..
...
// To help during migration: increase string length to 191
Schema::defaultStringLength(191);
```

### Modifying Columns
1. Install the package
```
composer require doctrine/dbal
```
2. Create a new migration
```
php artisan make:migration ModifyUsersTable
```
3. The attribute for modification:
```php
Schema::table('users', function (Blueprint $table) {
    $table->string('name', 50)->change();
});
```