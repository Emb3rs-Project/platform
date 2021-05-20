<?php

namespace App\Contracts\Embers\Simulations;

interface DestroysSimulations
{
    /**
     * Delete an existing Simulation.
     *
     * @param  mixed  $user
     * @param  int    $id
     * @return mixed
     */
    public function destroy(mixed $user, int $id);
}
