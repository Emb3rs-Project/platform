<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class Location extends Resource
{
    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = '0. Sinks&Sources';


    /**
     * The model the resource corresponds to.
     *
     * @var  string
     */
    public static $model = \App\Models\Location::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var  string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var  array
     */
    public static $search = [
        'id',


        // You can add any of this to your Laravel Nova Search
        //    'name',
        //    'description',
        //    'type'
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
            Text::make(__('NAME'), 'name'),
            Text::make(__('DESCRIPTION'), 'description'),
            Select::make(__('TYPE'), 'type')
                ->options([
                    'point' => __('point'),
                    'circle' => __('circle'),
                    'polygon' => __('polygon')
                ]),
            Code::make(__('DATA'), 'data')
                ->json()
                ->rules('json'),

            BelongsTo::make(__('PROJECT'), 'project', Project::class)->nullable(),
            HasMany::make(__('PROJECTS'), 'projects', Project::class),
            HasMany::make(__('INSTANCES'), 'instances', Instance::class),

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
