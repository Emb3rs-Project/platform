<?php

namespace App\Contracts\Embers\Objects\Sources;

interface EditsSources
{
    /**
     * Display the necessary objects for updating a given Source.
     *
     * @param  mixed  $user
     * @param  int    $id
     * @return mixed
     */
    public function edit(mixed $user, int $id);

    /**
     * Redirect to a specific page after updating an existing source.
     * //TODO
     */
    public function redirectTo();
}
