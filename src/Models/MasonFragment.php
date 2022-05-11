<?php

namespace Zareismail\Mason\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; 

class MasonFragment extends Model
{
    use Concerns\Activable; 
    use Concerns\Fallback;   
    use Concerns\GeneratesOperator;  
    use Concerns\InteractsWithLayout;   
    use HasFactory;   

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'active'    => 'boolean',
        'fallback'  => 'boolean',
    ];

    /**
     * Query the rlated MasonComponent.
     * 
     * \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function component()
    {
        return $this->belongsTo(MasonComponent::class);
    }

    /**
     * Get corresponding  operator.
     *
     * @return string
     */
    public function cypressOperator()
    {
        return strval(
            app()->getNamespace() . "Mason\\Fragments\\" . $this->operatorName()
        );
    }

    /**
     * Get generator command.
     * 
     * @return strig.
     */
    public function command(): string
    {
        return 'fragment';
    }

    /**
     * Get the `uriKey` of corresponding fragment.
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

        return $this->component->getUrl($uri);
    }

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param  array  $models
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function newCollection(array $models = [])
    {
        return new Collections\FragmentCollection($models);
    }
}
