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