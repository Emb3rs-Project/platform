<?php

namespace App\Rules\Embers;

use App\HasEmbersPermissions;
use Illuminate\Contracts\Validation\Rule;

class TeamRole implements Rule
{
    use HasEmbersPermissions;

    /**
     * The available permissions of the application.
     *
     * @var array
     */
    private array $permissions;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->permissions = $this->getFriendlyPermissionNames()->all();
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return in_array($value, $this->permissions);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('The :attribute must be a valid permission.');
    }
}
