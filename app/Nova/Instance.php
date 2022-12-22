<?php

namespace App\Nova;

use App\Nova\Actions\ImportSink;
use App\Nova\Actions\ImportSource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Http\Requests\NovaRequest;

class Instance extends Resource
{

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = '0.0 - Templates';

    /**
     * The model the resource corresponds to.
     *
     * @var  string
     */
    public static $model = \App\Models\Instance::class;

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
        //    'values',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return  array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),
            Text::make(__('NAME'), 'name'),
            Code::make(__('VALUES'), 'values')->json()->rules('json'),

            BelongsTo::make(__('TEMPLATE'), 'template', Template::class),
            BelongsTo::make(__('LOCATION'), 'location', Location::class),
            BelongsToMany::make(__('TEAMS'), 'teams', Team::class),

            BelongsToMany::make(__('GROUPING'), 'grouping', Instance::class)
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param \Illuminate\Http\Request $request
     * @return  array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return  array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return  array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return  array
     */
    public function actions(Request $request)
    {
        return [
            (new ImportSink())
                ->standalone(),
            (new ImportSource())
                ->standalone()
        ];
    }
}
