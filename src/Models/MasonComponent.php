<?php

namespace Zareismail\Mason\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Zareismail\Mason\Mason;

class MasonComponent extends Model
{
    use Concerns\Activable; 
    use Concerns\Fallback;   
    use Concerns\GeneratesOperator;  
    use Concerns\InteractsWithLayout;  
    use Concerns\Maintainable;  
    use HasFactory;   

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'active' => 'boolean',
        'fallback' => 'boolean',
    ];

    /**
     * Perform any actions required after the model boots.
     *
     * @return void
     */
    protected static function booted()
    {
        static::saved(function() {
            Mason::forget(); 
        });
        static::deleted(function() {
            Mason::forget();
        });
    }

    /**
     * Get generator command.
     * 
     * @return strig.
     */
    public function command(): string
    {
        return 'component';
    }

    /**
     * Get the `uriKey` of corresponding component.
     * 
     * @return string
     */
    public function uriKey()
    {
        return trim($this->slug, '/');
    }

    /**
     * Get the url for given uri.
     * 
     * @param  string $uri 
     * @return string      
     */
    public function getUrl($uri = '')
    {
        if (! $this->isFallback()) {
            $uri = $this->uriKey().'/'.trim($uri, '/');
        }

        return url($uri);
    }

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param  array  $models
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function newCollection(array $models = [])
    {
        return new Collections\ComponentCollection($models);
    }
}
