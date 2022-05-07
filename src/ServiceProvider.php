<?php

namespace Zareismail\Mason;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as LaravelServiceProvider;   
use Laravel\Nova\Nova; 
use Zareismail\Cypress\Cypress;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [  
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        $this->registerResources();

        Nova::serving(function () {
            $this->servingNova();
        });

        Cypress::discover(app_path('Mason'));
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
            Nova::$resourcesByModel[$model] = $resource;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    { 
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
