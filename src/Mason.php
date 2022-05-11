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
    public static $components = [
        \Zareismail\Mason\Cypress\Blank::class,   
    ];

    /**
     * The registered fragment names.
     *
     * @var array
     */
    public static $fragments = [
        \Zareismail\Mason\Cypress\Fragments\Blank::class,  
    ];

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
     * Register the given fragments.
     *
     * @param  array  $fragments
     * @return static
     */
    public static function fragments(array $fragments)
    {
        static::$fragments = array_unique(
            array_merge(static::$fragments, $fragments)
        );

        return new static;
    } 

    /**
     * Return the base collection of Cypress fragments.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function fragmentCollection()
    {
        return Collection::make(static::$fragments);
    }

    /**
     * Get components from cache.
     * 
     * @return \Illuminate\Support\Collection
     */
    public static function cachedComponents()
    {
        $resource = config('mason.resources.'. Nova\Component::class);

        return \Cache::sear($resource::uriKey(), function() use ($resource) { 
            $callback = function($component) {
                $component->fragments->each->setRelation('component', $component);
            };

            return $resource::newModel()->with('layout', 'fragments.layout')->get()->each($callback);
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
