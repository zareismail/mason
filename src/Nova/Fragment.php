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

class Fragment extends Resource
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

            BelongsTo::make(__('Display Through'), 'component', Component::class) 
                ->required()
                ->filterable()
                ->rules('required')
                ->withoutTrashed()
                ->showCreateRelationButton(),

            Select::make(__('Operator'), 'operator')
                ->options(static::fragments($request))
                ->displayUsingLabels() 
                ->filterable()
                ->required()
                ->rules('required')
                ->help(__('Determine which fragment should manage it.')),

            BelongsTo::make(__('Display By'), 'layout', Layout::class) 
                ->required()
                ->filterable()
                ->rules('required')
                ->withoutTrashed()
                ->showCreateRelationButton(), 

            Text::make(__('Fragment Name'), 'name')
                ->onlyOnForms()
                ->filterable()
                ->sortable()
                ->required()
                ->rules('required')
                ->placeholder(__('New Mason Fragment')),

            Slug::make(__('Fragment Prefix'), 'slug') 
                ->from('name') 
                ->hideFromIndex()
                ->sortable()
                ->required()
                ->rules([
                    'required',
                    Rule::unique('mason_fragments')
                        ->ignore($this->id)
                        ->where(function($query) {
                            return $query->where('component_id', $this->component_id);
                        }),
                ]), 

            Boolean::make(__('Properly Configured'), function() {
                return $this->hasOperator();
            })->onlyOnDetail(), 

            Boolean::make(__('Available To Users'), 'active')
                ->help(__('Determine if fragment is available to view by users.'))
                ->hideFromIndex()
                ->filterable()
                ->sortable(), 

            Boolean::make(__('Fallback Fragment'), 'fallback')
                ->help(__('Determine if you need to ignore prefixing fragment paths'))
                ->hideFromIndex()
                ->filterable()
                ->sortable()
                ->rules([
                    Rule::unique('mason_fragments')
                        ->ignore($this->id)
                        ->where(function($query) {
                            return $query->where($this->getQualifiedFallbackColumn(), true)
                                         ->where('component_id', $this->component_id);
                        }),
                ]),

            Panel::make(__('SEO'), [

                Text::make(__('Fragment Title'), 'title')
                    ->sortable()
                    ->filterable()
                    ->hideFromIndex()
                    ->placeholder(__('Blog'))
                    ->rules('max:60'),

                Textarea::make(__('Fragment Description'), 'description')
                    ->placeholder(__('Some description for my component hould enter here'))
                    ->hideFromIndex()
                    ->rules('max:250'),
            ]), 
        ];
    }   

    /**
     * Get available fragments for select options.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Support\Collection           
     */
    public function fragments(NovaRequest $request)
    {
        return Mason::fragmentCollection()->flip()->map(function($key, $fragment) {
            return is_callable([$fragment, 'label']) 
                ? $fragment::label() 
                : __(class_basename($fragment));
        });
    }
}
