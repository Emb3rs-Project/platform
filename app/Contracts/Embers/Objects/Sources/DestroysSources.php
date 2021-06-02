<?php

namespace App\Contracts\Embers\Objects\Sources;

interface DestroysSources
{
    /**
     * Delete an existing Source.
     *
     * @param  mixed  $user
     * @param  int  $id
     * @return void
     */
    public function destroy($user, int $id);
}
