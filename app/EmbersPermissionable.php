<?php

namespace App;

use Illuminate\Support\Str;

trait EmbersPermissionable
{
    /**
    * The name of the action this trait is being used inside.
    *
    * @return string
    */
    public function getActionName(): string
    {
        return self::class;
    }

    /**
     * The name (excluding the namespace) of the action this trait is being used inside.
     *
     * @return string
     */
    public function getShortActionName(): string
    {
        return class_basename(self::class);
    }

    /**
     * The friendly name of the action this trait is being used inside.
     *
     * @return string
     */
    public function getFriendlyActionName(): string
    {
        $actionName = $this->getShortActionName();

        $friendlyActionName = Str::of($actionName)->kebab()->replace('-', ' ');

        return $friendlyActionName;
    }

    /**
     * Determine if the user is authorized to access this action.
     *
     * @param  mixed  $user
     * @return void
     */
    public function authorize($user): void
    {
        $team = $user->currentTeam;

        $permission = $this->getActionName();

        abort_unless($user->hasTeamPermission($team, $permission), 401);
    }
}