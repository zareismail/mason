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
