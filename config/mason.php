<?php  

use Zareismail\Mason\Models\MasonComponent;
use Zareismail\Mason\Models\MasonLayout;
use Zareismail\Mason\Nova\Component; 
use Zareismail\Mason\Nova\Layout; 

return [ 

    /*
    |--------------------------------------------------------------------------
    | Mason Resources Classe
    |--------------------------------------------------------------------------
    |
    | This configuration option allows you to specify custom resources class
    | to use instead of the type that ships with Mason. You may use this to
    | define any extra form fields or other custom behavior as required.
    |
    */
   
	'resources' => [   
        Component::class => Component::class,
        Layout::class => Layout::class,
	],

    /*
    |--------------------------------------------------------------------------
    | Mason Resources Model Classes
    |--------------------------------------------------------------------------
    |
    | This configuration option allows you to specify custom resources model
    | to use instead of the type that ships with Mason.
    |
    */
   
    'models' => [ 
        Component::class => MasonComponent::class,
        Layout::class => MasonLayout::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Mason Model Policy Classes
    |--------------------------------------------------------------------------
    |
    | This configuration option allows you to specify custom policy class
    | to use instead of the type that ships with Mason.
    |
    */
   
    'policies' => [ 
        MasonComponent::class   => \Zareismail\Mason\Policies\Component::class,
        MasonLayout::class   => \Zareismail\Mason\Policies\Layout::class,
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Mason Supported Locales
    |--------------------------------------------------------------------------
    |
    | This configuration option allows you to add or remove locale from Mason component.
    |
    */
   
    'locales' => [ 
        'en' => 'English',
    ], 
];