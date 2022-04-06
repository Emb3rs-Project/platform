<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class Simulation extends Resource
{
    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = '1.0 - Simulations';

    /**
     * The model the resource corresponds to.
     *
     * @var  string
     */
    public static $model = \App\Models\Simulation::class;

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
        //    'status',
        //    'targetData',
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
            Select::make(__('STATUS'), 'status')
                ->options([
                    'NEW' => __('New'),
                    'IN PREPARATION' => __('In Prepation'),
                    'READY' => __('Ready'),
                    'ANALYSING' => __('Analysing'),
                    'STOPPED' => __('Stopped'),
                    'ERROR' => __('Error')
                    // 'new' => __('New'),
                    // 'in-preparation' => __('In Prepation'),
                    // 'ready' => __('Ready'),
                    // 'analysing' => __('Analysing'),
                    // 'stopped' => __('Stopped'),
                    // 'error' => __('Error')
                ]),
            Code::make(__('TARGETDATA'), 'targetData')->json()->rules('json'),

            BelongsTo::make(__('PROJECT'), 'project', Project::class),
            BelongsTo::make(__('TARGET'), 'target', Target::class),
            BelongsTo::make(__('SIMULATIONTYPE'), 'simulationType', SimulationType::class),

            BelongsTo::make(__('SIMULATIONMETADATA'), 'simulationMetadata', SimulationMetadata::class)
                ->nullable(),

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
