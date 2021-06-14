<?php

namespace App;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

trait EmbersPermissionable
{
    /**
    * The ID that is going to define this permission in the frontend.
    *
    * @return string
    */
    public function getId(): string
    {
        // return hash_hmac('sha512/256', $this->getActionName(), config('APP_KEY'));
        return Hash::make($this->getActionName());
    }

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
    * The name of the group this trait is being used inside.
    *
    * @return string
    */
    public function getGroupName(): string
    {
        $action = $this->getActionName();

        return Str::of($action)->beforeLast('\\')->afterLast('\\');
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
