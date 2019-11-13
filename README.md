# Exposé
Guide for my Laravel Applications.

## Creating Traits
I had to get this in place because an upcoming project needs to use UUID for its models. <br>
Also, **Traits** are good for exporting functions/logic that can be used by multiple classes. You get more standardized and readable code by using Traits.

However, Laravel (<= 6.0) does not come with a simple `artisan` command for creating traits. Hence, this branch.

https://github.com/cedextech/trait-generator

1. Install package
```
composer require cedextech/trait-generator --dev

```
2. Register service provider
```
Cedextech\TraitGenerator\ServiceProvider::class,
```
3. Test
```
php artisan make:trait TraitNqme
```