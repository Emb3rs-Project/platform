<?php

namespace App\Contracts\Embers\Objects;

use App\Models\Instance;

interface UpdatesSources
{
    /**
     * Validate and update an existing source.
     *
     * @param  mixed    $user
     * @param  Instance $source
     * @param  array    $input
     * @return mixed
     */
    public function update($user, Instance $source, array $input);

    /**
     * Redirect to a specific page after updating an existing source.
     * //TODO
     */
    public function redirectTo();
}
