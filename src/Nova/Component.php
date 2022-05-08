<?php

namespace Zareismail\Mason\Nova;
 
use Illuminate\Validation\Rule;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean; 
use Laravel\Nova\Fields\BooleanGroup; 
use Laravel\Nova\Fields\ID; 
use Laravel\Nova\Fields\Select; 
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Stack;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Url;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Zareismail\Mason\Mason;

class Component extends Resource
{
    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(), 

            Url::make(__('Name'), function() {
                return $this->getUrl();
            })->displayUsing(function() {
                return $this->name;
            })->sortable(),  

            BooleanGroup::make(__('Status'))
                ->onlyOnIndex()
                ->options([
                    'configured'=> __('Properly Configured'),
                    'active'    => __('Is Available'), 
                    'fallback'  => __('Is Fallback'),
                ])
                ->resolveUsing(function() {
                    return [
                        'active'    => $this->isActive(), 
                        'fallback'  => $this->isFallback(),
                        'configured'=> $this->hasOperator(),
                    ];
                }),

            Select::make(__('Operator'), 'operator')
                ->options(static::components($request))
                ->displayUsingLabels() 
                ->filterable()
                ->required()
                ->rules('required')
                ->help(__('Determine which component should manage it.')),

            BelongsTo::make(__('Display By'), 'layout', Layout::class) 
                ->required()
                ->filterable()
                ->rules('required')
                ->withoutTrashed()
                ->showCreateRelationButton(),

            Select::make(__('Language'), 'locale')
                ->options((array) config('mason.locales'))
                ->displayUsingLabels()
                ->filterable()
                ->sortable()
                ->required()
                ->rules('required')
                ->help(__('Determine application locale when component runs.'))
                ->default(function()  {
                    $locales = array_keys((array) config('mason.locales'));

                    return in_array(app()->getLocale(), $locales) ? app()->getLocale() : current($locales);
                }),

            Text::make(__('Component Name'), 'name')
                ->onlyOnForms()
                ->filterable()
                ->sortable()
                ->required()
                ->rules('required')
                ->placeholder(__('New Mason Component')),

            Slug::make(__('Component Directory'), 'slug') 
                ->from('name') 
                ->hideFromIndex()
                ->sortable()
                ->required()
                ->rules([
                    'required',
                    Rule::unique('mason_components')
                        ->ignore($this->id)
                        ->where(function($query) {
                            return $query->where('locale', '!=', $this->locale);
                        }),
                ]), 

            Boolean::make(__('Properly Configured'), function() {
                return $this->hasOperator();
            })->onlyOnDetail(), 

            Boolean::make(__('Available To Users'), 'active')
                ->help(__('Determine if component is available to view by users.'))
                ->hideFromIndex()
                ->filterable()
                ->sortable(), 

            Boolean::make(__('Fallback Component'), 'fallback')
                ->help(__('Determine if you need to ignore prefixing component paths'))
                ->hideFromIndex()
                ->filterable()
                ->sortable()
                ->rules([
                    Rule::unique('mason_components')
                        ->ignore($this->id)
                        ->where(function($query) {
                            return $query->where($this->getQualifiedFallbackColumn(), true);
                        }),
                ]),

            Panel::make(__('SEO'), [

                Text::make(__('Component Title'), 'title')
                    ->sortable()
                    ->filterable()
                    ->hideFromIndex()
                    ->placeholder(__('Blog'))
                    ->rules('max:60'),

                Text::make(__('Component Subtitle'), 'subtitle')
                    ->sortable()
                    ->filterable()
                    ->hideFromIndex()
                    ->placeholder(__('Home Page'))
                    ->rules('max:60'),

                Textarea::make(__('Component Description'), 'description')
                    ->placeholder(__('Some description for my component hould enter here'))
                    ->hideFromIndex()
                    ->rules('max:250'),
            ]), 
        ];
    }   

    /**
     * Get available components for select options.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Support\Collection           
     */
    public function components(NovaRequest $request)
    {
        return Mason::componentCollection()->flip()->map(function($key, $component) {
            return is_callable([$component, 'label']) 
                ? $component::label() 
                : __(class_basename($component));
        });
    }
}
