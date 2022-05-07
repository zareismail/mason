<?php

namespace Zareismail\Mason\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; 

class MasonLayout extends Model
{
    use Concerns\Configurable;
    use HasFactory;  

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [ 
    ];  
}
