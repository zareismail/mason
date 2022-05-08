<?php

namespace Zareismail\Mason\Models\Concerns;  

trait Activable 
{     
    /**
     * Update database with active state.
     *
     * @return $this
     */
    public function activate()
    {
        return $this->update([
            $this->getActiveColumn() => $this->getActiveState() 
        ]);
    }

    /**
     * Update database with inactive state.
     *
     * @return $this
     */
    public function inactivate()
    {
        return $this->update([
            $this->getActiveColumn() => $this->getInactiveState() 
        ]);
    } 

    /**
     * Determine that model is on active state.
     * 
     * @param  string $value 
     * @return bool       
     */
    public function isActive()
    {
        return $this->getAttribute($this->getActiveColumn()) === $this->getActiveState();
    }

    /**
     * Determine that model is on inactive state.
     * 
     * @param  string $value 
     * @return bool       
     */
    public function isNotActive()
    {
        return $this->getAttribute($this->getActiveColumn()) !== $this->getActiveState();
    }

    /**
     * Query the model's by the active state.
     * 
     * @param  \Illuminate\Database\Eloquent\Builder $query  
     * @return \Illuminate\Database\Eloquent\Builder       
     */
    public function scopeActives($query)
    {
        return $query->where($this->getQualifiedActiveColumn(), $this->getActiveState());
    }

    /**
     * Query the model's by the inactive state.
     * 
     * @param  \Illuminate\Database\Eloquent\Builder $query  
     * @return \Illuminate\Database\Eloquent\Builder       
     */
    public function scopeInactives($query)
    {
        return $query->where($this->getQualifiedActiveColumn(), '!=', $this->getActiveState());
    }  

    /**
     * Get the value of the "active" column.
     *
     * @return string
     */
    public function getActiveState()
    {
        return defined('static::ACTIVE_VALUE') ? static::ACTIVE_VALUE : true;
    }

    /**
     * Get the value of the "inactive" column.
     *
     * @return string
     */
    public function getInactiveState()
    {
        return defined('static::INACTIVE_VALUE') ? static::INACTIVE_VALUE : false;
    }

    /**
     * Get the name of the "active" column.
     *
     * @return string
     */
    public function getActiveColumn()
    {
        return defined('static::ACTIVE') ? static::ACTIVE : 'active';
    }

    /**
     * Get the fully qualified "active" column.
     *
     * @return string
     */
    public function getQualifiedActiveColumn()
    {
        return $this->qualifyColumn($this->getActiveColumn());
    }
}
