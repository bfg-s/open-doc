<?php

namespace Bfg\OpenDoc;

use App\Providers\OpenDocumentationProvider;
use Bfg\OpenDoc\BladeDirectives\MarkdownDirective;
use Bfg\OpenDoc\Commands\DocGenerateCommand;
use Illuminate\Support\Facades\Blade;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'open-doc');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'open-doc');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/routes.php');

        if (class_exists(OpenDocumentationProvider::class)) {
            $this->app->register(OpenDocumentationProvider::class);
        }

        Blade::directive('markdown', fn ($e) => MarkdownDirective::directive($e));

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/open-doc.php' => config_path('open-doc.php'),
            ], 'config');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/open-doc'),
            ], 'views');*/

            // Publishing assets.
            $this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/open-doc'),
            ], 'open-doc-assets');

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/open-doc'),
            ], 'lang');*/

            // Registering package commands.
             $this->commands([
                 DocGenerateCommand::class
             ]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/open-doc.php',
            'open-doc'
        );

        $this->app->singleton('open-doc', function () {
            return new OpenDoc();
        });
    }
}
