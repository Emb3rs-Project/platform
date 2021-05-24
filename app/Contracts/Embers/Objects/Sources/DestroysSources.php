<?php

namespace App\Contracts\Embers\Objects\Sources;

interface DestroysSources
{
    /**
     * Delete an existing Source.
     *
     * @param  int  $id
     * @return mixed
     */
    public function destroy(int $id);
}
