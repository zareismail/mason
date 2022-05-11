<?php

namespace Zareismail\Mason\Models\Collections;
 
use Illuminate\Database\Eloquent\Collection; 

class BaseCollection extends Collection
{  
    /**
     * Filter items for the given operator.
     * 
     * @param  string $operator 
     * @return static          
     */
    public function forOperator(string $operator)
    { 
        return $this->filter->using($operator);
    }

    /**
     * Map items to corresponding cypress operator.
     * 
     * @param  string $operator 
     * @return static          
     */
    public function toOperator()
    { 
        return $this->filter->hasOperator()->map->cypressOperator();
    }
}
