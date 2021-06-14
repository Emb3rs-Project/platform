<?php

namespace App\Contracts\Embers\Objects\Links;

interface DestroysLinks
{
    /**
     * Delete an existing Link.
     *
     * @param  mixed  $user
     * @param  int  $id
     * @return void
     */
    public function destroy($user, int $id);
}
