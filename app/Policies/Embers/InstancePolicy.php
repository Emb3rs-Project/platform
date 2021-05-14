<?php

namespace App\Policies\Embers;

use App\Models\Instance;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Log;

class InstancePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        // TODO: Permissions check

        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Instance  $instance
     * @return mixed
     */
    public function view(User $user, Instance $instance)
    {
        // TODO: Permissions check

        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        // TODO: Permissions check

        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Instance  $instance
     * @return mixed
     */
    public function update(User $user, Instance $instance)
    {
        // TODO: Permissions check

        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Instance  $instance
     * @return mixed
     */
    public function delete(User $user, Instance $instance)
    {
        // TODO: Permissions check

        return true;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Instance  $instance
     * @return mixed
     */
    public function restore(User $user, Instance $instance)
    {
        // TODO: Permissions check

        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Instance  $instance
     * @return mixed
     */
    public function forceDelete(User $user, Instance $instance)
    {
        // TODO: Permissions check

        return true;
    }
}
