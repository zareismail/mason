<?php

namespace Zareismail\Mason\Models\Concerns;  

trait Fallback 
{     
    /**
     * Determine if the value of the model's "fallback" attribute is true.
     * 
     * @return bool       
     */
    public function isFallback()
    {
        return boolval($this->{$this->getFallbackColumn()});
    } 

    /**
     * Query the model's where`fallback` is true.
     * 
     * @param  \Illuminate\Database\Eloquent\Builder $query  
     * @return \Illuminate\Database\Eloquent\Builder       
     */
    public function scopeFallback($query)
    {
        return $this->where($this->getQualifiedFallbackColumn(), true);
    }   

    /**
     * Get the fully qualified "fallback" column.
     *
     * @return string
     */
    public function getQualifiedFallbackColumn()
    {
        return $this->qualifyColumn($this->getFallbackColumn());
    }

    /**
     * Get the value of the "fallback" mark.
     *
     * @return string
     */
    public function getFallbackColumn()
    {
        return defined('static::FLLABACK') ? static::FLLABACK : 'fallback';
    } 
}
