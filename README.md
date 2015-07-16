# Laravel-Subdomain-Localization

Subdomain localization support for Laravel.

## Table of Contents

- <a href="#installation">Installation</a>
    - <a href="#composer">Composer</a>
    - <a href="#manually">Manually</a>
    - <a href="#laravel">Laravel</a>
- <a href="#usage">Usage</a>
    - <a href="#locale-detection">Locale detection</a>
    - <a href="#middleware">Middleware</a>
    - <a href="#route-translation">Route translation</a>

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

Laravel comes with a middleware that can be used to enforce the use of a language subdomain.

If you want to use it, open `app/Http/kernel.php` and register this route middleware by adding it to the `routeMiddleware` array:

```php
	...
    'localize' => \LaurentEsc\Localization\Middleware\Localization::class,
	...
```

## Usage

### Locale detection

Open `app/Providers/RouteServiceProvider.php` and add a call to detectLocale() from the boot method. For example, using the facade:

```php
	...
	use LaurentEsc\Localization\Facades\Localize;
	...
    public function boot(Router $router)
    {
        // This will guess a locale from the current HTTP request
        // and set the application locale
        Localize::detectLocale();
        
        parent::boot($router);
    }
	...
```

Once you have done this, there is nothing more that you MUST do. Laravel application locale has been set and you can use other locale-dependant Laravel components (e.g. Translation) as you normally do.

### Middleware

If you want to enforce the use of a language subdomain for some routes, you can simply assign the middleware provided, for example as follows in `app/Http/routes.php`:

```php
    // Without the localize middleware, this route can be reached with or without language subdomain
    Route::get('logout', 'AuthController@logout');
    
    // With the localize middleware, this route cannot be reached without language subdomain
    Route::group([ 'middleware' => [ 'localize' ]], function() {
    
        Route::get('welcome', 'WelcomeController@index');
    
    });
```

For more information about Middleware, please refer to <a href="http://laravel.com/docs/middleware">Laravel docs</a>.

### Route translation

If you want to use translated routes (en.yourdomain.com/welcome, fr.yourdomain.com/bienvenue), proceed as follows:

First, create language files for the languages that you support:

`resources/lang/en/routes.php`:

```php
    return [
    
        // route name => route translation
        'welcome' => 'welcome',
    
    ];
```

`resources/lang/fr/routes.php`:

```php
    return [
    
        // route name => route translation
        'welcome' => 'bienvenue',
    
    ];
```

Then, here is how you define translated routes in `app/Http/routes.php`:

```php    
    Route::group([ 'middleware' => [ 'localize' ]], function() {
    
        Route::get(Router::resolve('routes.welcome'), 'WelcomeController@index');
    
    });
```

You can of course name the language files as you wish, and pass the proper prefix (routes. in the example) to the resolve() method.