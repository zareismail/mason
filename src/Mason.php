<?php

namespace Zareismail\Mason; 

use Illuminate\Support\Collection;
use Zareismail\Cypress\Cypress;

class Mason extends Cypress
{     
    /**
     * Indicates if Mason should register its migrations.
     *
     * @var bool
     */
    public static $runsMigrations = false;
    
    /**
     * The registered component names.
     *
     * @var array
     */
    public static $components = [];

    /**
     * Configure Mason to not register its migrations.
     *
     * @return static
     */
    public static function ignoreMigrations()
    {
        static::$runsMigrations = false;

        return new static();
    }

    /**
     * Get components from cache.
     * 
     * @return \Illuminate\Support\Collection
     */
    public static function cachedComponents()
    {
        $resource = config('mason.resources.'. Nova\Component::class);
        $ttl = \Auth::guard(config('nova.guard'))->check() ? 0 : now()->addDay();

        return \Cache::remember($resource::uriKey(), $ttl, function() use ($resource) { 
            return $resource::newModel()->with('layout')->get();
        }); 
    }   

    /**
     * Forget all of the caches.
     * 
     * @return $this
     */
    public static function forget()
    {
        collect(config('mason.resources'))->each(function($resource) {
            \Cache::forget($resource::uriKey());
        });

        return new static;
    }

    /**
     * Dynamically proxy static method calls.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     *
     * @throws \BadMethodCallException
     */
    public static function __callStatic($method, $parameters)
    {
        if (! property_exists(get_called_class(), $method)) {
            throw new BadMethodCallException("Method {$method} does not exist.");
        }

        return static::${$method};
    }
}
