<?php

namespace App\Contracts\Embers\Objects\Sources;

interface DestroysSources
{
    /**
     * Delete an existing Source.
     *
     * @param  mixed   $user
     * @param  string  $id
     * @return mixed
     */
    public function destroy(mixed $user, string $id);

    /**
     * Redirect to a specific page after updating an existing source.
     * //TODO
     */
    public function redirectTo();
}
