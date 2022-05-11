<?php

namespace Zareismail\Mason; 
 
use Zareismail\Mason\MasonLayout;
use Zareismail\Mason\Models\MasonComponent; 
use Zareismail\Mason\Nova\Component; 

trait ResolvesFragment
{       
    /**
     * Resolve the resoruce's value for the given request.
     *
     * @param  \Zareismail\Cypress\Http\Requests\CypressRequest  $request 
     * @return void
     */
    public function resolve($request): bool
    {       
        if (is_callable([parent::class, 'resolve']) && parent::resolve($request) === false) {
            return false;
        } 

        $this->withMeta([
            'title' => $this->title($request),
            'description' => $this->description($request),
        ]);

        $request->resolveComponent()->withMeta([
            'subtitle' => $this->title($request),
            'description' => $this->description($request),
        ]); 

        return static::fragment()->isActive() || \Auth::guard(config('nova.guard'))->check();
    } 

    /**
     * Get title to display on meta.
     * 
     * @param  \Zareismail\Cypress\Http\Requests\CypressRequest  $request 
     * @return string          
     */
    public function title($request): string
    {  
        return method_exists(parent::class, 'title')
            ? parent::title($request)
            : static::fragment()->title;  
    } 

    /**
     * Get description to display on meta.
     * 
     * @param  \Zareismail\Cypress\Http\Requests\CypressRequest  $request 
     * @return string          
     */
    public function description($request): string
    { 
        return method_exists(parent::class, 'description')
            ? parent::description($request)
            : static::fragment()->description; 
    } 

    /**
     * Get the URI key for the resource.
     *
     * @return string
     */
    public static function uriKey()
    {
        return static::fragment()->uriKey();
    }
     
    /**
     * Determine if the fragment is a fallback fragment.
     *
     * @return boolean
     */
    public static function fallback(): bool
    { 
        return static::fragment()->isFallback();
    }   

    /**
     * Get the component coresponding fragment.
     * 
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function fragment()
    {
        return once(function() {
            return Mason::cachedComponents()->flatMap->fragments->first(function($fragment) {
                return $fragment->operatorName() === class_basename(static::class);
            });
        });
    } 

    /**
     * Get  the component coresponding component.
     * 
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function component()
    { 
        return Mason::cachedComponents()->first(function($component) {
            return $component->fragments->first(function($fragment) {
                return $fragment->operatorName() === class_basename(static::class);
            });
        }); 
    }  
}
