<?php

namespace Zareismail\Mason;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as LaravelServiceProvider;   
use Laravel\Nova\Nova; 
use Zareismail\Cypress\Cypress;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerResources();
        $this->registerPolicies();

        Nova::serving(function () {
            $this->servingNova();
        });

        Cypress::discover(app_path('Mason'));
    }   

    /**
     * Get the policies defined on the provider.
     *
     * @return array
     */
    public function policies()
    {
        return (array) config('mason.policies', []);
    }

    /**
     * Register any Nova serives.
     *  
     * @return void
     */
    protected function servingNova()
    { 
        Nova::resources((array) config('mason.resources'));
        
        collect(config('mason.models'))->each(function($model, $resource) {
            Nova::$resourcesByModel[$model] = config("mason.resources.{$resource}");
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    { 
        $this->commands([
            Console\ComponentCommand::class, 
            Console\FragmentCommand::class, 
        ]);
    }

    /**
     * Register the package resources such as routes, templates, etc.
     *
     * @return void
     */
    protected function registerResources()
    { 
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'mason');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->mergeConfigFrom(__DIR__.'/../config/mason.php', 'mason');  

        if (Mason::runsMigrations()) {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }
    }
}
