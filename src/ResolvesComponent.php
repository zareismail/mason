<?php

namespace Zareismail\Mason; 
 
use Zareismail\Mason\MasonLayout;
use Zareismail\Mason\Models\MasonComponent; 
use Zareismail\Mason\Nova\Component; 

trait ResolvesComponent
{      
    /**
     * Resolve the resoruce's value for the given request.
     *
     * @param  \Zareismail\Cypress\Http\Requests\CypressRequest  $request 
     * @return void
     */
    public function resolve($request): bool
    { 
        app()->setLocale(static::component()->locale);

        if (is_callable([parent::class, 'resolve']) && ! parent::resolve($request)) {
            return false;
        }

        return static::component()->isActive() || \Auth::guard(config('nova.guard'))->check();
    }

    /**
     * Get the URI key for the resource.
     *
     * @return string
     */
    public static function uriKey()
    {
        return static::component()->uriKey();
    }
     
    /**
     * Determine if the component is a fallback component.
     *
     * @return boolean
     */
    public static function fallback(): bool
    { 
        return static::component()->isFallback();
    }

    /**
     * Get  the component coresponding component.
     * 
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function component()
    {
        return Mason::cachedComponents()->first(function($component) {
            return $component->cypressOperator() === static::class;
        }); 
    } 

    /**
     * Get the component fragments.
     *
     * @return string
     */
    public function fragments(): array
    {
        return [];
    }

    /**
     * Get the layout instance.
     * 
     * @return string                  
     */
    public function resolveLayout()
    { 
        return MasonLayout::make(); 
    }

    /**
     * Prepare the resource for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array_merge(parent::jsonSerialize(), [ 
            'title' => static::component()->title,
            'subtitle' => static::component()->subtitle,
            'description' => static::component()->description,
        ]);
    }
}
