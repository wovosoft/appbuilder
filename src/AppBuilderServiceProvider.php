<?php

namespace Wovosoft\AppBuilder;

use Illuminate\Support\ServiceProvider;
use Wovosoft\AppBuilder\Commands\AppBuilderDelete;
use Wovosoft\AppBuilder\Commands\AppBuilderInstall;
use Wovosoft\AppBuilder\Commands\AppBuilderMake;

class AppBuilderServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'appbuilder');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'appbuilder');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('appbuilder.php'),
            ], 'config');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/appbuilder'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/appbuilder'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/appbuilder'),
            ], 'lang');*/

            // Registering package commands.
            $this->commands([
                AppBuilderMake::class,
                AppBuilderDelete::class,
                AppBuilderInstall::class
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'appbuilder');

        // Register the main class to use with the facade
        $this->app->singleton('appbuilder', function () {
            return new AppBuilder;
        });
    }
}
