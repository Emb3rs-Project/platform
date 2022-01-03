<?php

namespace App;

use App\Contracts\Embers\Help\IndexesHelp;
use App\Contracts\Embers\Integration\ReportsSimulationSteps;
use App\Contracts\Embers\Notifications\IndexesNotifications;
use App\Contracts\Embers\Notifications\MarksAllNotificationsAsRead;
use App\Contracts\Embers\Objects\Links\CreatesLinks;
use App\Contracts\Embers\Objects\Links\DestroysLinks;
use App\Contracts\Embers\Objects\Links\EditsLinks;
use App\Contracts\Embers\Objects\Links\IndexesLinks;
use App\Contracts\Embers\Objects\Links\SharesLinks;
use App\Contracts\Embers\Objects\Links\ShowsLinks;
use App\Contracts\Embers\Objects\Links\StoresLinks;
use App\Contracts\Embers\Objects\Links\UpdatesLinks;
use App\Contracts\Embers\Objects\Sinks\CreatesSinks;
use App\Contracts\Embers\Objects\Sinks\DestroysSinks;
use App\Contracts\Embers\Objects\Sinks\EditsSinks;
use App\Contracts\Embers\Objects\Sinks\IndexesSinks;
use App\Contracts\Embers\Objects\Sinks\SharesSinks;
use App\Contracts\Embers\Objects\Sinks\ShowsSinks;
use App\Contracts\Embers\Objects\Sinks\StoresSinks;
use App\Contracts\Embers\Objects\Sinks\UpdatesSinks;
use App\Contracts\Embers\Objects\Sources\CreatesSources;
use App\Contracts\Embers\Objects\Sources\DestroysSources;
use App\Contracts\Embers\Objects\Sources\EditsSources;
use App\Contracts\Embers\Objects\Sources\IndexesSources;
use App\Contracts\Embers\Objects\Sources\SharesSources;
use App\Contracts\Embers\Objects\Sources\ShowsSources;
use App\Contracts\Embers\Objects\Sources\StoresSources;
use App\Contracts\Embers\Objects\Sources\UpdatesSources;
use App\Contracts\Embers\Projects\CreatesProjects;
use App\Contracts\Embers\Projects\DestroysProjects;
use App\Contracts\Embers\Projects\EditsProjects;
use App\Contracts\Embers\Projects\IndexesProjects;
use App\Contracts\Embers\Projects\ShowsProjects;
use App\Contracts\Embers\Projects\StoresProjects;
use App\Contracts\Embers\Projects\UpdatesProjects;
use App\Contracts\Embers\Projects\SharesProjects;
use App\Contracts\Embers\Simulations\SharesSimulations;
use App\Contracts\Embers\Simulations\CreatesSimulations;
use App\Contracts\Embers\Simulations\DestroysSimulations;
use App\Contracts\Embers\Simulations\EditsSimulations;
use App\Contracts\Embers\Simulations\IndexesSimulations;
use App\Contracts\Embers\Simulations\ShowsSimulations;
use App\Contracts\Embers\Simulations\StoresSimulations;
use App\Contracts\Embers\Simulations\UpdatesSimulations;
use App\Contracts\Embers\TeamRoles\CreatesTeamRoles;
use App\Contracts\Embers\TeamRoles\DestroysTeamRoles;
use App\Contracts\Embers\TeamRoles\EditsTeamRoles;
use App\Contracts\Embers\TeamRoles\IndexesTeamRoles;
use App\Contracts\Embers\TeamRoles\ShowsTeamRoles;
use App\Contracts\Embers\TeamRoles\StoresTeamRoles;
use App\Contracts\Embers\TeamRoles\UpdatesTeamRoles;
use App\Contracts\Embers\Teams\AddsTeamMembers;
use App\Contracts\Embers\Teams\InvitesTeamMembers;
use App\Contracts\Embers\Teams\UpdatesTeamMemberRoles;
use App\Contracts\Embers\MapData\IndexesMapData;
use App\Contracts\Embers\MapData\StoresMapData;
use App\Contracts\Embers\News\IndexesNews;
use App\Contracts\Embers\News\ShowsNews;
use App\Contracts\Embers\Notifications\DestroysNotifications;
use App\Contracts\Embers\Notifications\MarksNotificationsAsRead;
use App\Contracts\Embers\Notifications\RemovesAllNotifications;
use App\Contracts\Embers\Search\QueriesSearch;

class Embers
{
    /**
     * Register a class / callback that should be used to index all Sinks.
     *
     * @param  string  $class
     * @return void
     */
    public static function indexSinksUsing(string $class): void
    {
        app()->singleton(IndexesSinks::class, $class);
    }

    /**
     * Register a class / callback that should be used to display the necessary
     * objects for the creation of a Sink.
     *
     * @param  string  $class
     * @return void
     */
    public static function createSinksUsing(string $class): void
    {
        app()->singleton(CreatesSinks::class, $class);
    }

    /**
     * Register a class / callback that should be used to display a given Sink.
     *
     * @param  string  $class
     * @return void
     */
    public static function showSinksUsing(string $class): void
    {
        app()->singleton(ShowsSinks::class, $class);
    }

    /**
     * Register a class / callback that should be used to create Sinks.
     *
     * @param  string  $class
     * @return void
     */
    public static function storeSinksUsing(string $class): void
    {
        app()->singleton(StoresSinks::class, $class);
    }

    /**
     * Register a class / callback that should be used to display the necessary
     * objects for the updating of a given Sink.
     *
     * @param  string  $class
     * @return void
     */
    public static function editSinksUsing(string $class): void
    {
        app()->singleton(EditsSinks::class, $class);
    }

    /**
     * Register a class / callback that should be used to update a given Sink.
     *
     * @param  string  $class
     * @return void
     */
    public static function updateSinksUsing(string $class): void
    {
        app()->singleton(UpdatesSinks::class, $class);
    }

    /**
     * Register a class / callback that should be used to delete a given Sink.
     *
     * @param  string  $class
     * @return void
     */
    public static function destroySinksUsing(string $class): void
    {
        app()->singleton(DestroysSinks::class, $class);
    }

    /**
     * Register a class / callback that should be used to share a given Sink.
     *
     * @param  string  $class
     * @return void
     */
    public static function shareSinksUsing(string $class): void
    {
        app()->singleton(SharesSinks::class, $class);
    }

    /**
     * Register a class / callback that should be used to index all Sources.
     *
     * @param  string  $class
     * @return void
     */
    public static function indexSourcesUsing(string $class): void
    {
        app()->singleton(IndexesSources::class, $class);
    }

    /**
     * Register a class / callback that should be used to display the necessary
     * objects for the creation of a Source.
     *
     * @param  string  $class
     * @return void
     */
    public static function createSourcesUsing(string $class): void
    {
        app()->singleton(CreatesSources::class, $class);
    }

    /**
     * Register a class / callback that should be used to display a given
     * Source.
     *
     * @param  string  $class
     * @return void
     */
    public static function showSourcesUsing(string $class): void
    {
        app()->singleton(ShowsSources::class, $class);
    }

    /**
     * Register a class / callback that should be used to create Sources.
     *
     * @param  string  $class
     * @return void
     */
    public static function storeSourcesUsing(string $class): void
    {
        app()->singleton(StoresSources::class, $class);
    }

    /**
     * Register a class / callback that should be used to display the necessary
     * objects for the updating of a given Source.
     *
     * @param  string  $class
     * @return void
     */
    public static function editSourcesUsing(string $class): void
    {
        app()->singleton(EditsSources::class, $class);
    }

    /**
     * Register a class / callback that should be used to update a given Source.
     *
     * @param  string  $class
     * @return void
     */
    public static function updateSourcesUsing(string $class): void
    {
        app()->singleton(UpdatesSources::class, $class);
    }

    /**
     * Register a class / callback that should be used to delete a given Source.
     *
     * @param  string  $class
     * @return void
     */
    public static function destroySourcesUsing(string $class): void
    {
        app()->singleton(DestroysSources::class, $class);
    }

    /**
     * Register a class / callback that should be used to share a given Source.
     *
     * @param  string  $class
     * @return void
     */
    public static function shareSourcesUsing(string $class): void
    {
        app()->singleton(SharesSources::class, $class);
    }

    /**
     * Register a class / callback that should be used to index all Links.
     *
     * @param  string  $class
     * @return void
     */
    public static function indexLinksUsing(string $class): void
    {
        app()->singleton(IndexesLinks::class, $class);
    }

    /**
     * Register a class / callback that should be used to display the necessary
     * objects for the creation of a Link.
     *
     * @param  string  $class
     * @return void
     */
    public static function createLinksUsing(string $class): void
    {
        app()->singleton(CreatesLinks::class, $class);
    }

    /**
     * Register a class / callback that should be used to create Links.
     *
     * @param  string  $class
     * @return void
     */
    public static function storeLinksUsing(string $class): void
    {
        app()->singleton(StoresLinks::class, $class);
    }

    /**
     * Register a class / callback that should be used to display a given Link.
     *
     * @param  string  $class
     * @return void
     */
    public static function showLinksUsing(string $class): void
    {
        app()->singleton(ShowsLinks::class, $class);
    }

    /**
     * Register a class / callback that should be used to display the necessary
     * objects for the updating of a given Link.
     *
     * @param  string  $class
     * @return void
     */
    public static function editLinksUsing(string $class): void
    {
        app()->singleton(EditsLinks::class, $class);
    }

    /**
     * Register a class / callback that should be used to update a given Link.
     *
     * @param  string  $class
     * @return void
     */
    public static function updateLinksUsing(string $class): void
    {
        app()->singleton(UpdatesLinks::class, $class);
    }

    /**
     * Register a class / callback that should be used to delete a given Link.
     *
     * @param  string  $class
     * @return void
     */
    public static function destroyLinksUsing(string $class): void
    {
        app()->singleton(DestroysLinks::class, $class);
    }

    /**
     * Register a class / callback that should be used to share a given Link.
     *
     * @param  string  $class
     * @return void
     */
    public static function shareLinksUsing(string $class): void
    {
        app()->singleton(SharesLinks::class, $class);
    }

    /**
     * Register a class / callback that should be used to index all Projects.
     *
     * @param  string  $class
     * @return void
     */
    public static function indexProjectsUsing(string $class): void
    {
        app()->singleton(IndexesProjects::class, $class);
    }

    /**
     * Register a class / callback that should be used to display the necessary
     * objects for the creation of a Project.
     *
     * @param  string  $class
     * @return void
     */
    public static function createProjectsUsing(string $class): void
    {
        app()->singleton(CreatesProjects::class, $class);
    }

    /**
     * Register a class / callback that should be used to create Projects.
     *
     * @param  string  $class
     * @return void
     */
    public static function storeProjectsUsing(string $class): void
    {
        app()->singleton(StoresProjects::class, $class);
    }

    /**
     * Register a class / callback that should be used to display a given
     * Project.
     *
     * @param  string  $class
     * @return void
     */
    public static function showProjectsUsing(string $class): void
    {
        app()->singleton(ShowsProjects::class, $class);
    }

    /**
     * Register a class / callback that should be used to display the necessary
     * objects for the updating of a given Project.
     *
     * @param  string  $class
     * @return void
     */
    public static function editProjectsUsing(string $class): void
    {
        app()->singleton(EditsProjects::class, $class);
    }

    /**
     * Register a class / callback that should be used to update a given
     * Project.
     *
     * @param  string  $class
     * @return void
     */
    public static function updateProjectsUsing(string $class): void
    {
        app()->singleton(UpdatesProjects::class, $class);
    }

    /**
     * Register a class / callback that should be used to delete a given
     * Project.
     *
     * @param  string  $class
     * @return void
     */
    public static function destroyProjectsUsing(string $class): void
    {
        app()->singleton(DestroysProjects::class, $class);
    }

    /**
     * Register a class / callback that should be used to share a given Project.
     *
     * @param  string  $class
     * @return void
     */
    public static function shareProjectsUsing(string $class): void
    {
        app()->singleton(SharesProjects::class, $class);
    }

    /**
     * Register a class / callback that should be used to index all Simulations.
     *
     * @param  string  $class
     * @return void
     */
    public static function indexSimulationsUsing(string $class): void
    {
        app()->singleton(IndexesSimulations::class, $class);
    }

    /**
     * Register a class / callback that should be used to display the necessary
     * objects for the creation of a Simulation.
     *
     * @param  string  $class
     * @return void
     */
    public static function createSimulationsUsing(string $class): void
    {
        app()->singleton(CreatesSimulations::class, $class);
    }

    /**
     * Register a class / callback that should be used to create Simulation.
     *
     * @param  string  $class
     * @return void
     */
    public static function storeSimulationsUsing(string $class): void
    {
        app()->singleton(StoresSimulations::class, $class);
    }

    /**
     * Register a class / callback that should be used to display a given
     * Simulation.
     *
     * @param  string  $class
     * @return void
     */
    public static function showSimulationsUsing(string $class): void
    {
        app()->singleton(ShowsSimulations::class, $class);
    }

    /**
     * Register a class / callback that should be used to display the necessary
     * objects for the updating of a given Simulation.
     *
     * @param  string  $class
     * @return void
     */
    public static function editSimulationsUsing(string $class): void
    {
        app()->singleton(EditsSimulations::class, $class);
    }

    /**
     * Register a class / callback that should be used to update a given
     * Simulation.
     *
     * @param  string  $class
     * @return void
     */
    public static function updateSimulationsUsing(string $class): void
    {
        app()->singleton(UpdatesSimulations::class, $class);
    }

    /**
     * Register a class / callback that should be used to delete a given
     * Simulation.
     *
     * @param  string  $class
     * @return void
     */
    public static function destroySimulationsUsing(string $class): void
    {
        app()->singleton(DestroysSimulations::class, $class);
    }

    /**
     * Register a class / callback that should be used to share a given
     * Simulation.
     *
     * @param  string  $class
     * @return void
     */
    public static function shareSimulationsUsing(string $class): void
    {
        app()->singleton(SharesSimulations::class, $class);
    }

    /**
     * Register a class / callback that should be used to index all Roles.
     *
     * @param  string  $class
     * @return void
     */
    public static function indexTeamRolesUsing(string $class): void
    {
        app()->singleton(IndexesTeamRoles::class, $class);
    }

    /**
     * Register a class / callback that should be used to display the necessary
     * permissions for the creation of a TeamRole.
     *
     * @param  string  $class
     * @return void
     */
    public static function createTeamRolesUsing(string $class): void
    {
        app()->singleton(CreatesTeamRoles::class, $class);
    }

    /**
     * Register a class / callback that should be used to create TeamRole.
     *
     * @param  string  $class
     * @return void
     */
    public static function storeTeamRolesUsing(string $class): void
    {
        app()->singleton(StoresTeamRoles::class, $class);
    }

    /**
     * Register a class / callback that should be used to display a given
     * TeamRole.
     *
     * @param  string  $class
     * @return void
     */
    public static function showTeamRolesUsing(string $class): void
    {
        app()->singleton(ShowsTeamRoles::class, $class);
    }

    /**
     * Register a class / callback that should be used to display the necessary
     * permissions for the updating of a given TeamRole.
     *
     * @param  string  $class
     * @return void
     */
    public static function editTeamRolesUsing(string $class): void
    {
        app()->singleton(EditsTeamRoles::class, $class);
    }

    /**
     * Register a class / callback that should be used to update a given
     * TeamRole.
     *
     * @param  string  $class
     * @return void
     */
    public static function updateTeamRolesUsing(string $class): void
    {
        app()->singleton(UpdatesTeamRoles::class, $class);
    }

    /**
     * Register a class / callback that should be used to delete a given
     * TeamRole.
     *
     * @param  string  $class
     * @return void
     */
    public static function destroyTeamRolesUsing(string $class): void
    {
        app()->singleton(DestroysTeamRoles::class, $class);
    }

    /**
     * Register a class / callback that should be used to add team members.
     *
     * @param  string  $class
     * @return void
     */
    public static function addTeamMembersUsing(string $class): void
    {
        app()->singleton(AddsTeamMembers::class, $class);
    }

    /**
     * Register a class / callback that should be used to add team members by
     * sending email invites.
     *
     * @param  string  $class
     * @return void
     */
    public static function inviteTeamMembersUsing(string $class): void
    {
        app()->singleton(InvitesTeamMembers::class, $class);
    }

    /**
     * Register a class / callback that should be used to update a team member's
     * role inside a team.
     *
     * @param  string  $class
     * @return void
     */
    public static function updateTeamMemberRolesUsing(string $class): void
    {
        app()->singleton(UpdatesTeamMemberRoles::class, $class);
    }

    /**
     * Register a class / callback that should be used to index all
     *  Notifications.
     *
     * @param  string  $class
     * @return void
     */
    public static function indexNotificationsUsing(string $class): void
    {
        app()->singleton(IndexesNotifications::class, $class);
    }

    /**
     * Register a class / callback that should be used to mark all Notifications
     * as read.
     *
     * @param  string  $class
     * @return void
     */
    public static function markAllNotificationsAsReadUsing(string $class): void
    {
        app()->singleton(MarksAllNotificationsAsRead::class, $class);
    }

    /**
     * Register a class / callback that should be used to mark a Notification as
     * read.
     *
     * @param  string  $class
     * @return void
     */
    public static function markNotificationAsReadUsing(string $class): void
    {
        app()->singleton(MarksNotificationsAsRead::class, $class);
    }

    /**
     * Register a class / callback that should be used to delete a given
     * Notification.
     *
     * @param  string  $class
     * @return void
     */
    public static function destroyNotificationsUsing(string $class): void
    {
        app()->singleton(DestroysNotifications::class, $class);
    }

    /**
     * Register a class / callback that should be used to delete all available
     * Notifications.
     *
     * @param  string  $class
     * @return void
     */
    public static function removeAllNotificationsUsing(string $class): void
    {
        app()->singleton(RemovesAllNotifications::class, $class);
    }

    /**
     * Register a class / callback that should be used to index all User's Map
     * data.
     *
     * @param  string  $class
     * @return void
     */
    public static function indexMapDataUsing(string $class): void
    {
        app()->singleton(IndexesMapData::class, $class);
    }

    /**
     * Register a class / callback that should be used to create User's Map
     * data.
     *
     * @param  string  $class
     * @return void
     */
    public static function storeMapDataUsing(string $class): void
    {
        app()->singleton(StoresMapData::class, $class);
    }

    /**
     * Register a class / callback that should be used to query the Searchable
     * Models.
     *
     * @param  string  $class
     * @return void
     */
    public static function querySearchableModelsUsing(string $class): void
    {
        app()->singleton(QueriesSearch::class, $class);
    }

    /**
     * Register a class / callback that should be used to index all News.
     *
     * @param  string  $class
     * @return void
     */
    public static function indexNewsUsing(string $class): void
    {
        app()->singleton(IndexesNews::class, $class);
    }

    /**
     * Register a class / callback that should be used to display a given News.
     *
     * @param  string  $class
     * @return void
     */
    public static function showNewsUsing(string $class): void
    {
        app()->singleton(ShowsNews::class, $class);
    }

    /**
     * Register a class / callback that should be used to index all Faqs.
     *
     * @param  string  $class
     * @return void
     */
    public static function indexHelpUsing(string $class): void
    {
        app()->singleton(IndexesHelp::class, $class);
    }

    /**
     * Register a class / callback that should be used to report simulation steps.
     *
     * @param  string  $class
     * @return void
     */
    public static function reportSimulationStepReportsUsing(string $class): void
    {
        app()->singleton(ReportsSimulationSteps::class, $class);
    }
}
