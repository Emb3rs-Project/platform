<?php
namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Http\Requests\NovaRequest;

class SimulationManager extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var  string
     */
    public static $model = \App\Models\SimulationManager::class;

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
//    'version',
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
            Text::make(__('VERSION'),'version'),
            
            BelongsTo::make(__('USER'),'user', User::class ),
            BelongsTo::make(__('SIMULATIONSTATUS'),'simulationStatus', SimulationStatus::class ),
            HasMany::make(__('SIMULATIONOUTPUTS'),'simulationOutputs', SimulationOutput::class ),
            HasMany::make(__('SIMULATIONRUNS'),'simulationRuns', SimulationRun::class ),
            HasMany::make(__('SIMULATIONMANAGERGEOAREAS'),'simulationManagerGeoAreas', SimulationManagerGeoArea::class ),
            HasMany::make(__('SIMULATIONMANAGEROBJECTINSTANCES'),'simulationManagerObjectInstances', SimulationManagerObjectInstance::class ),
            HasMany::make(__('SIMULATIONCONSTRAINTINSTANCES'),'simulationConstraintInstances', SimulationConstraintInstance::class ),
            HasMany::make(__('SIMULATIONTARGETINSTANCES'),'simulationTargetInstances', SimulationTargetInstance::class ),
            HasMany::make(__('SIMULATIONTYPEINSTANCES'),'simulationTypeInstances', SimulationTypeInstance::class ),
            HasMany::make(__('SIMULATIONMANAGERSHARINGTYPES'),'simulationManagerSharingTypes', SimulationManagerSharingType::class ),
            
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
