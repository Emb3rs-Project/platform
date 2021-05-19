<?php

namespace App\Contracts\Embers\Objects\Links;

interface DestroysLinks
{
    /**
     * Delete an existing Link.
     *
     * @param  mixed   $user
     * @param  string  $id
     * @return mixed
     */
    public function destroy(mixed $user, string $id);
}
