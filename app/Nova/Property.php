<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class Property extends Resource
{
    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = '0.2 - Properties';


    /**
     * The model the resource corresponds to.
     *
     * @var  string
     */
    public static $model = \App\Models\Property::class;

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
        'name'



        // You can add any of this to your Laravel Nova Search
        //    'name',
        //    'dataType',
        //    'inputType',
        //    'data',
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
            Text::make(__('SYMBOLICNAME'), 'symbolic_name'),
            Text::make(__('DESCRIPTION'), 'description'),
            Select::make(__('DATATYPE'), 'dataType')
                ->options(
                    [
                        "number" => __('Number'),
                        "datetime" => __('DateTime'),
                        "string" => __('String'),
                        "float" => __('Float')
                    ]
                )
                ->displayUsingLabels(),
            Select::make(__('INPUTTYPE'), 'inputType')
                ->options(
                    [
                        "text" => __('Text Input'),
                        "datetime" => __('Date Input'),
                        "select" => __('Select / ComboBox'),
                        "week_schedule" => __('Week Schedule Input')
                    ]
                )
                ->displayUsingLabels(),
            Code::make(__('DATA'), 'data')->json()->rules('json'),

            HasMany::make(__('TEMPLATEPROPERTIES'), 'templateProperties', TemplateProperty::class),
            BelongsToMany::make(__('UNITPROPERTY'), 'units', Unit::class),
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
