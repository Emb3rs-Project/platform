<?php

namespace App\Http\Controllers\Embers;

use App\Contracts\Embers\Teams\AddsTeamMembers;
use App\Http\Controllers\Controller;
use App\Models\TeamInvitation;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TeamInvitationController extends Controller
{
    /**
     * Accept a team invitation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  TeamInvitation  $invitation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function accept(Request $request, TeamInvitation $invitation)
    {
        app(AddsTeamMembers::class)->add(
            $invitation->team->owner,
            $invitation->team,
            $request->all()
        );

        $invitation->delete();

        return redirect(config('fortify.home'))->banner(
            __('Great! You have accepted the invitation to join the :team team.', ['team' => $invitation->team->name]),
        );
    }

    /**
     * Cancel the given team invitation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  TeamInvitation  $invitation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, TeamInvitation $invitation)
    {
        if (!Gate::forUser($request->user())->check('removeTeamMember', $invitation->team)) {
            throw new AuthorizationException();
        }

        $invitation->delete();

        return back(303);
    }
}
