<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ActionServiceProvider extends ServiceProvider
{
    /**
     * All of the container singletons that should be registered.
     *
     * @var array
     */
    public $singletons = [
        \App\Contracts\Embers\Objects\Sinks\IndexesSinks::class => \App\Actions\Embers\Objects\Sinks\IndexSink::class,
        \App\Contracts\Embers\Objects\Sinks\CreatesSinks::class => \App\Actions\Embers\Objects\Sinks\CreateSink::class,
        \App\Contracts\Embers\Objects\Sinks\ShowsSinks::class => \App\Actions\Embers\Objects\Sinks\ShowSink::class,
        \App\Contracts\Embers\Objects\Sinks\StoresSinks::class => \App\Actions\Embers\Objects\Sinks\StoreSink::class,
        \App\Contracts\Embers\Objects\Sinks\EditsSinks::class => \App\Actions\Embers\Objects\Sinks\EditSink::class,
        \App\Contracts\Embers\Objects\Sinks\UpdatesSinks::class => \App\Actions\Embers\Objects\Sinks\UpdateSink::class,
        \App\Contracts\Embers\Objects\Sinks\DestroysSinks::class => \App\Actions\Embers\Objects\Sinks\DestroySink::class,
        \App\Contracts\Embers\Objects\Sinks\SharesSinks::class => \App\Actions\Embers\Objects\Sinks\ShareSink::class,
        \App\Contracts\Embers\Objects\Sources\IndexesSources::class => \App\Actions\Embers\Objects\Sources\IndexSource::class,
        \App\Contracts\Embers\Objects\Sources\CreatesSources::class => \App\Actions\Embers\Objects\Sources\CreateSource::class,
        \App\Contracts\Embers\Objects\Sources\ShowsSources::class => \App\Actions\Embers\Objects\Sources\ShowSource::class,
        \App\Contracts\Embers\Objects\Sources\StoresSources::class => \App\Actions\Embers\Objects\Sources\StoreSource::class,
        \App\Contracts\Embers\Objects\Sources\EditsSources::class => \App\Actions\Embers\Objects\Sources\EditSource::class,
        \App\Contracts\Embers\Objects\Sources\UpdatesSources::class => \App\Actions\Embers\Objects\Sources\UpdateSource::class,
        \App\Contracts\Embers\Objects\Sources\DestroysSources::class => \App\Actions\Embers\Objects\Sources\DestroySource::class,
        \App\Contracts\Embers\Objects\Sources\SharesSources::class => \App\Actions\Embers\Objects\Sources\ShareSource::class,
        \App\Contracts\Embers\Objects\Links\IndexesLinks::class => \App\Actions\Embers\Objects\Links\IndexLink::class,
        \App\Contracts\Embers\Objects\Links\CreatesLinks::class => \App\Actions\Embers\Objects\Links\CreateLink::class,
        \App\Contracts\Embers\Objects\Links\StoresLinks::class => \App\Actions\Embers\Objects\Links\StoreLink::class,
        \App\Contracts\Embers\Objects\Links\ShowsLinks::class => \App\Actions\Embers\Objects\Links\ShowLink::class,
        \App\Contracts\Embers\Objects\Links\EditsLinks::class => \App\Actions\Embers\Objects\Links\EditLink::class,
        \App\Contracts\Embers\Objects\Links\UpdatesLinks::class => \App\Actions\Embers\Objects\Links\UpdateLink::class,
        \App\Contracts\Embers\Objects\Links\DestroysLinks::class => \App\Actions\Embers\Objects\Links\DestroyLink::class,
        \App\Contracts\Embers\Objects\Links\SharesLinks::class => \App\Actions\Embers\Objects\Links\ShareLink::class,
        \App\Contracts\Embers\Projects\IndexesProjects::class => \App\Actions\Embers\Projects\IndexProject::class,
        \App\Contracts\Embers\Projects\CreatesProjects::class => \App\Actions\Embers\Projects\CreateProject::class,
        \App\Contracts\Embers\Projects\StoresProjects::class => \App\Actions\Embers\Projects\StoreProject::class,
        \App\Contracts\Embers\Projects\ShowsProjects::class => \App\Actions\Embers\Projects\ShowProject::class,
        \App\Contracts\Embers\Projects\EditsProjects::class => \App\Actions\Embers\Projects\EditProject::class,
        \App\Contracts\Embers\Projects\UpdatesProjects::class => \App\Actions\Embers\Projects\UpdateProject::class,
        \App\Contracts\Embers\Projects\DestroysProjects::class => \App\Actions\Embers\Projects\DestroyProject::class,
        \App\Contracts\Embers\Projects\SharesProjects::class => \App\Actions\Embers\Projects\ShareProject::class,
        \App\Contracts\Embers\Simulations\IndexesSimulations::class => \App\Actions\Embers\Simulations\IndexSimulation::class,
        \App\Contracts\Embers\Simulations\CreatesSimulations::class => \App\Actions\Embers\Simulations\CreateSimulation::class,
        \App\Contracts\Embers\Simulations\StoresSimulations::class => \App\Actions\Embers\Simulations\StoreSimulation::class,
        \App\Contracts\Embers\Simulations\ShowsSimulations::class => \App\Actions\Embers\Simulations\ShowSimulation::class,
        \App\Contracts\Embers\Simulations\EditsSimulations::class => \App\Actions\Embers\Simulations\EditSimulation::class,
        \App\Contracts\Embers\Simulations\UpdatesSimulations::class => \App\Actions\Embers\Simulations\UpdateSimulation::class,
        \App\Contracts\Embers\Simulations\DestroysSimulations::class => \App\Actions\Embers\Simulations\DestroySimulation::class,
        \App\Contracts\Embers\Simulations\SharesSimulations::class => \App\Actions\Embers\Simulations\ShareSimulation::class,
        \App\Contracts\Embers\TeamRoles\IndexesTeamRoles::class => \App\Actions\Embers\TeamRoles\IndexTeamRole::class,
        \App\Contracts\Embers\TeamRoles\CreatesTeamRoles::class => \App\Actions\Embers\TeamRoles\CreateTeamRole::class,
        \App\Contracts\Embers\TeamRoles\StoresTeamRoles::class => \App\Actions\Embers\TeamRoles\StoreTeamRole::class,
        \App\Contracts\Embers\TeamRoles\ShowsTeamRoles::class => \App\Actions\Embers\TeamRoles\ShowTeamRole::class,
        \App\Contracts\Embers\TeamRoles\EditsTeamRoles::class => \App\Actions\Embers\TeamRoles\EditTeamRole::class,
        \App\Contracts\Embers\TeamRoles\UpdatesTeamRoles::class => \App\Actions\Embers\TeamRoles\UpdateTeamRole::class,
        \App\Contracts\Embers\TeamRoles\DestroysTeamRoles::class => \App\Actions\Embers\TeamRoles\DestroyTeamRole::class,
        \App\Contracts\Embers\Teams\AddsTeamMembers::class => \App\Actions\Embers\Teams\AddTeamMember::class,
        \App\Contracts\Embers\Teams\InvitesTeamMembers::class => \App\Actions\Embers\Teams\InviteTeamMember::class,
        \App\Contracts\Embers\Teams\UpdatesTeamMemberRoles::class => \App\Actions\Embers\Teams\UpdateTeamMemberRole::class,
        \App\Contracts\Embers\Notifications\IndexesNotifications::class => \App\Actions\Embers\Notifications\IndexNotification::class,
        \App\Contracts\Embers\Notifications\MarksAllNotificationsAsRead::class => \App\Actions\Embers\Notifications\MarkAllNotificationsAsRead::class,
        \App\Contracts\Embers\Notifications\MarksNotificationsAsRead::class => \App\Actions\Embers\Notifications\MarkNotificationAsRead::class,
        \App\Contracts\Embers\Notifications\DestroysNotifications::class => \App\Actions\Embers\Notifications\DestroyNotification::class,
        \App\Contracts\Embers\Notifications\RemovesAllNotifications::class => \App\Actions\Embers\Notifications\RemoveAllNotifications::class,
        \App\Contracts\Embers\MapData\IndexesMapData::class => \App\Actions\Embers\MapData\IndexMapData::class,
        \App\Contracts\Embers\MapData\StoresMapData::class => \App\Actions\Embers\MapData\StoreMapData::class,
        \App\Contracts\Embers\Search\QueriesSearch::class => \App\Actions\Embers\Search\QuerySearch::class,
        \App\Contracts\Embers\News\IndexesNews::class => \App\Actions\Embers\News\IndexNews::class,
        \App\Contracts\Embers\News\ShowsNews::class => \App\Actions\Embers\News\ShowNews::class,
        \App\Contracts\Embers\Help\IndexesHelp::class => \App\Actions\Embers\Help\IndexHelp::class,
        \App\Contracts\Embers\Integration\ReportsSimulationSteps::class => \App\Actions\Embers\Integration\ReportSimulationStep::class,
        \App\Contracts\Embers\Integration\CharacterizesInstances::class => \App\Actions\Embers\Integration\CharacterizeInstance::class,
        \App\Contracts\Embers\Integration\ReportsSimulationFinishes::class => \App\Actions\Embers\Integration\ReportSimulationFinish::class,
        \App\Contracts\Embers\Integration\ReportsCharacterizationFinishes::class => \App\Actions\Embers\Integration\ReportCharacterizationFinish::class,
        \App\Contracts\Embers\Integration\StartsSimulations::class => \App\Actions\Embers\Integration\StartSimulation::class,
        \App\Contracts\Embers\Simulations\MySimulations::class => \App\Actions\Embers\Simulations\MySimulation::class
    ];
}
