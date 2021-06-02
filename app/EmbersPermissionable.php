<?php

namespace App;

trait EmbersPermissionable
{
    /**
     * The name (excluding the namespace) of the action this trait is being used inside.
     *
     * @return string
     */
    public function getShortActionName(): string
    {
        return substr(self::class, strrpos(self::class, '\\') + 1);
    }

    /**
     * The friendly name of the action this trait is being used inside.
     *
     * @return string
     */
    public function getFriendlyActionName(): string
    {
        $actionName = $this->getShortActionName();

        $actionNameArray = preg_split('/(?=[A-Z])/', $actionName);
        // Since we split on CAPITAL letters, we get an empty string in the beggining
        array_shift($actionNameArray);

        $friendlyActionName = 'can';
        foreach ($actionNameArray as &$part) {
            $part = strtolower($part);
            $friendlyActionName = $friendlyActionName . ' ' . $part;
        }
        unset($part);

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
        $friendlyActionName = $this->getFriendlyActionName();

        abort_unless($user->hasTeamPermission($user->currentTeam, $friendlyActionName), 401);
    }
}
