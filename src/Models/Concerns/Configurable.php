<?php

namespace Zareismail\Mason\Models\Concerns;  

trait Configurable 
{   
    /**
     * Initialize model instance.
     * 
     * @return void
     */
    public function initializeConfigurable()
    {
        $this->mergeCasts(['config' => 'array']);
    }

    /**
     * Get value from configrations.
     * 
     * @param  string $key     
     * @param  mixed $default 
     * @return mixed          
     */
    public function config($key = null, $default = null)
    {
        if ($key === null) {
            return (array) $this->getAttribute('config', []);
        }

        return data_get($this->config, $key, $value);
    }
}
