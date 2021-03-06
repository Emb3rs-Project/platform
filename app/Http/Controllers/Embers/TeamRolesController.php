<?php

namespace App\Http\Controllers\Embers;

use App\Contracts\Embers\TeamRoles\CreatesTeamRoles;
use App\Contracts\Embers\TeamRoles\DestroysTeamRoles;
use App\Contracts\Embers\TeamRoles\EditsTeamRoles;
use App\Contracts\Embers\TeamRoles\IndexesTeamRoles;
use App\Contracts\Embers\TeamRoles\ShowsTeamRoles;
use App\Contracts\Embers\TeamRoles\StoresTeamRoles;
use App\Contracts\Embers\TeamRoles\UpdatesTeamRoles;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TeamRolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $roles = app(IndexesTeamRoles::class)->index($request->user());

        return response()->json([
            'roles' => $roles
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $permissions = app(CreatesTeamRoles::class)->create($request->user());

        return response()->json([
            'permissions' => $permissions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $role = app(StoresTeamRoles::class)->store($request->user(), $request->all());

        // return Redirect::route('team-roles.index');
        $permissionSafeRole = app(ShowsTeamRoles::class)->show($request->user(), $role->id);

        return response()->json([
            'role' => $permissionSafeRole
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, int $id)
    {
        $role = app(ShowsTeamRoles::class)->show($request->user(), $id);

        return response()->json([
            'role' => $role
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request, int $id)
    {
        [$role, $permissions] = app(EditsTeamRoles::class)->edit($request->user(), $id);

        return response()->json([
            'role' => $role,
            'permissions' => $permissions
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, int $id)
    {
        $updatedRole = app(UpdatesTeamRoles::class)->update($request->user(), $id, $request->all());

        return redirect()->route('team-roles.show', $updatedRole->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, int $id)
    {
        app(DestroysTeamRoles::class)->destroy($request->user(), $id);

        return back(303);
    }
}
