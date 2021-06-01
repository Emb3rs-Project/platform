<?php

namespace App\Contracts\Embers\Objects\Links;

interface DestroysLinks
{
    /**
     * Delete an existing Link.
     *
     * @param  mixed  $user
     * @param  int  $id
     * @return mixed
     */
    public function destroy($user, int $id);
}
