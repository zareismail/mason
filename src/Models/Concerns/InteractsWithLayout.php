<?php

namespace Zareismail\Mason\Models\Concerns;  

use Zareismail\Mason\Models\MasonLayout;  

trait InteractsWithLayout 
{     
    /**
     * Query the rlated MasonLayout.
     * 
     * \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function layout()
    {
        return $this->belongsTo(MasonLayout::class);
    } 
}
