<?php
namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;

class TemplateProperties extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var  string
     */
    public static $model = \App\Models\TemplateProperties::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var  string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var  array
     */
    public static $search = [
        'id',


// You can add any of this to your Laravel Nova Search
//    'required',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),
            Boolean::make(__('REQUIRED'), 'required'),

            BelongsTo::make(__('TEMPLATE'), 'template', Template::class),
            BelongsTo::make(__('PROPERTY'), 'property', Properties::class),
            BelongsTo::make(__('UNIT'), 'unit', Unit::class),

        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
