# Laravel-Subdomain-Localization

Subdomain localization support for Laravel.

## Installation

### Composer

Add Laravel-Subdomain-Localization to your `composer.json` file.

    "laurentesc/laravel-subdomain-localization": "dev-master"

Run `composer install` to get the latest version of the package.

### Manually

It's recommended that you use Composer, however you can download and install from this repository.

### Laravel

Laravel-Subdomain-Localization comes with a service provider for Laravel.

To register the service provider in your Laravel application, open `config/app.php` and add the following line to the `providers` array:

```php
	...
	LaurentEsc\Localization\LocalizationServiceProvider::class
	...
```

Laravel-Subdomain-Localization comes with 2 facades: `Localize` and `Router`.

If you want to use them, open `config/app.php` and add the following lines to the `aliases` array:

```php
	...
    'Localize'  => LaurentEsc\Localization\Facades\Localize::class,
    'Router'    => LaurentEsc\Localization\Facades\Router::class,
	...
```

## Usage

TODO: Write usage instructions
