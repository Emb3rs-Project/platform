<?php

namespace App\Providers;

use App\Actions\Embers\Notifications\IndexNotification;
use App\Actions\Embers\Notifications\MarkAllNotificationsAsRead;
use App\Actions\Embers\Objects\Links\CreateLink;
use App\Actions\Embers\Objects\Links\DestroyLink;
use App\Actions\Embers\Objects\Links\EditLink;
use App\Actions\Embers\Objects\Links\IndexLink;
use App\Actions\Embers\Objects\Links\ShareLink;
use App\Actions\Embers\Objects\Links\ShowLink;
use App\Actions\Embers\Objects\Links\StoreLink;
use App\Actions\Embers\Objects\Links\UpdateLink;
use App\Actions\Embers\Projects\IndexProject;
use App\Actions\Embers\Objects\Sinks\CreateSink;
use App\Actions\Embers\Objects\Sinks\DestroySink;
use App\Actions\Embers\Objects\Sinks\EditSink;
use App\Actions\Embers\Objects\Sinks\IndexSink;
use App\Actions\Embers\Objects\Sinks\ShareSink;
use App\Actions\Embers\Objects\Sinks\ShowSink;
use App\Actions\Embers\Objects\Sinks\StoreSink;
use App\Actions\Embers\Objects\Sinks\UpdateSink;
use App\Actions\Embers\Objects\Sources\CreateSource;
use App\Actions\Embers\Objects\Sources\DestroySource;
use App\Actions\Embers\Objects\Sources\EditSource;
use App\Actions\Embers\Objects\Sources\IndexSource;
use App\Actions\Embers\Objects\Sources\ShareSource;
use App\Actions\Embers\Objects\Sources\ShowSource;
use App\Actions\Embers\Objects\Sources\StoreSource;
use App\Actions\Embers\Objects\Sources\UpdateSource;
use App\Actions\Embers\Projects\CreateProject;
use App\Actions\Embers\Projects\DestroyProject;
use App\Actions\Embers\Projects\EditProject;
use App\Actions\Embers\Projects\ShareProject;
use App\Actions\Embers\Projects\ShowProject;
use App\Actions\Embers\Projects\StoreProject;
use App\Actions\Embers\Projects\UpdateProject;
use App\Actions\Embers\Simulations\CreateSimulation;
use App\Actions\Embers\Simulations\DestroySimulation;
use App\Actions\Embers\Simulations\EditSimulation;
use App\Actions\Embers\Simulations\IndexSimulation;
use App\Actions\Embers\Simulations\ShareSimulation;
use App\Actions\Embers\Simulations\ShowSimulation;
use App\Actions\Embers\Simulations\StoreSimulation;
use App\Actions\Embers\Simulations\UpdateSimulation;
use App\Actions\Embers\TeamRoles\CreateTeamRole;
use App\Actions\Embers\TeamRoles\DestroyTeamRole;
use App\Actions\Embers\TeamRoles\EditTeamRole;
use App\Actions\Embers\TeamRoles\IndexTeamRole;
use App\Actions\Embers\TeamRoles\ShowTeamRole;
use App\Actions\Embers\TeamRoles\StoreTeamRole;
use App\Actions\Embers\TeamRoles\UpdateTeamRole;
use App\Actions\Embers\Teams\AddTeamMember;
use App\Actions\Embers\Teams\InviteTeamMember;
use App\Actions\Embers\Teams\UpdateTeamMemberRole;
use App\Actions\Embers\Users\IndexUserMapData;
use App\Actions\Embers\Users\StoreUserMapData;
use App\Embers;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Embers::indexSinksUsing(IndexSink::class);
        Embers::createSinksUsing(CreateSink::class);
        Embers::storeSinksUsing(StoreSink::class);
        Embers::showSinksUsing(ShowSink::class);
        Embers::editSinksUsing(EditSink::class);
        Embers::updateSinksUsing(UpdateSink::class);
        Embers::destroySinksUsing(DestroySink::class);
        Embers::shareSinksUsing(ShareSink::class);

        Embers::indexSourcesUsing(IndexSource::class);
        Embers::createSourcesUsing(CreateSource::class);
        Embers::showSourcesUsing(ShowSource::class);
        Embers::storeSourcesUsing(StoreSource::class);
        Embers::editSourcesUsing(EditSource::class);
        Embers::updateSourcesUsing(UpdateSource::class);
        Embers::destroySourcesUsing(DestroySource::class);
        Embers::shareSourcesUsing(ShareSource::class);

        Embers::indexLinksUsing(IndexLink::class);
        Embers::createLinksUsing(CreateLink::class);
        Embers::storeLinksUsing(StoreLink::class);
        Embers::showLinksUsing(ShowLink::class);
        Embers::editLinksUsing(EditLink::class);
        Embers::updateLinksUsing(UpdateLink::class);
        Embers::destroyLinksUsing(DestroyLink::class);
        Embers::shareLinksUsing(ShareLink::class);

        Embers::indexProjectsUsing(IndexProject::class);
        Embers::createProjectsUsing(CreateProject::class);
        Embers::storeProjectsUsing(StoreProject::class);
        Embers::showProjectsUsing(ShowProject::class);
        Embers::editProjectsUsing(EditProject::class);
        Embers::updateProjectsUsing(UpdateProject::class);
        Embers::destroyProjectsUsing(DestroyProject::class);
        Embers::shareProjectsUsing(ShareProject::class);

        Embers::indexSimulationsUsing(IndexSimulation::class);
        Embers::createSimulationsUsing(CreateSimulation::class);
        Embers::storeSimulationsUsing(StoreSimulation::class);
        Embers::showSimulationsUsing(ShowSimulation::class);
        Embers::editSimulationsUsing(EditSimulation::class);
        Embers::updateSimulationsUsing(UpdateSimulation::class);
        Embers::destroySimulationsUsing(DestroySimulation::class);
        Embers::shareSimulationsUsing(ShareSimulation::class);

        Embers::indexTeamRolesUsing(IndexTeamRole::class);
        Embers::createTeamRolesUsing(CreateTeamRole::class);
        Embers::storeTeamRolesUsing(StoreTeamRole::class);
        Embers::showTeamRolesUsing(ShowTeamRole::class);
        Embers::editTeamRolesUsing(EditTeamRole::class);
        Embers::updateTeamRolesUsing(UpdateTeamRole::class);
        Embers::destroyTeamRolesUsing(DestroyTeamRole::class);

        Embers::addTeamMembersUsing(AddTeamMember::class);
        Embers::inviteTeamMembersUsing(InviteTeamMember::class);
        Embers::updateTeamMemberRolesUsing(UpdateTeamMemberRole::class);

        Embers::indexNotificationsUsing(IndexNotification::class);
        Embers::markAllNotificationsAsReadUsing(MarkAllNotificationsAsRead::class);

        Embers::indexUsersMapDataUsing(IndexUserMapData::class);
        Embers::storeUsersMapDataUsing(StoreUserMapData::class);
    }
}
