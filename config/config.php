<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Available locales
    |--------------------------------------------------------------------------
    |
    | An array of the locales accepted by the routing system.
    |
    */
    'available_locales' => ['en', 'de'],

    /*
    |--------------------------------------------------------------------------
    | Available locales
    |--------------------------------------------------------------------------
    |
    | Use this option to enable or disable the use of cookies
    | in locale detection.
    |
    */
    'cookie_localization' => true,

    /*
    |--------------------------------------------------------------------------
    | Available locales
    |--------------------------------------------------------------------------
    |
    | Use this option to enable or disable the use of the browser settings
    | in locale detection.
    |
    */
    'browser_localization' => true,

    /*
    |--------------------------------------------------------------------------
    | Available locales
    |--------------------------------------------------------------------------
    |
    | Here you may change the name of the cookie used to save the locale.
    | This option is used only if localization with cookie is enabled.
    |
    */
    'cookie_name' => 'locale',

    /*
    |--------------------------------------------------------------------------
    | Domain name
    |--------------------------------------------------------------------------
    |
    | Here you may change the name of the domain used in your application.
    | By default, the domain is read from the .env file.
    |
    */
    'domain' => env('DOMAIN'),

];