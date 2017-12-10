<?php namespace LaurentEsc\Localization;

use Illuminate\Support\ServiceProvider;

class LocalizationServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/config.php' => config_path('localization.php'),
        ], 'config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

        $packageConfigFile = __DIR__ . '/../config/config.php';
        $this->mergeConfigFrom(
            $packageConfigFile, 'localization'
        );

        $this->app->singleton('localization.localize', function () {
                return new Localize();
            }
        );

        $this->app->singleton('localization.router', function () {
                return new Router();
            }
        );

    }

}
