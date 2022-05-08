<?php

namespace Zareismail\Mason;

use Illuminate\Http\Request;
use Zareismail\Cypress\Layout;   

class MasonLayout extends Layout
{        
    /**
     * Get the viewName name for the layout.
     *
     * @return string
     */
    public function viewName(): string
    {
        return 'mason::layout';
    }  

    /**
     * Get the widgets available on the entity.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function widgets(Request $request)
    { 
        return [];
    }

    /**
     * Get the layout for the given request.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Datbase\Eloquent\Model
     */
    public function layout(Request $request)
    {
        return $request->isFragmentRequest() 
            ? $this->resolveFragmentLayout($request)
            : $this->resolveComponentLayout($request);
    }

    /**
     * Get the layout from fragment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function resolveFragmentLayout(Request $request)
    {
        return tap($request->resolveFragment()->fragment()->layout($request), function($layout) {
            abort_if(is_null($layout), 422, 'Not found any layout to display fragment');
        }); 
    } 

    /**
     * Get the layout from component.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function resolveComponentLayout(Request $request)
    {
        return tap($request->resolveComponent()->component()->layout($request), function($layout) {
            abort_if(is_null($layout), 422, 'Not found any layout to display component');
        });   
    }

    /**
     * Get the plugins available on the entity.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function plugins(Request $request)
    { 
        return [];
    } 

    /**
     * Prepare the resource for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        $rtl = data_get($this->layout($this->getRequest()), 'rtl');

        return array_merge(parent::jsonSerialize(), [
            'direction' => $rtl ? 'rtl' : 'ltr',
        ]);
    }
}
