<?php

namespace App\Contracts\Embers\Objects\Links;

interface DestroysLinks
{
    /**
     * Delete an existing Link.
     *
     * @param  int    $id
     * @return mixed
     */
    public function destroy(int $id);
}
