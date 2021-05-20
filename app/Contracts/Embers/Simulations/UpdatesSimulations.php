<?php
namespace App\Contracts\Embers\Simulations;

interface UpdatesSimulations
{
    /**
     * Validate and update an existing Simulation.
     *
     * @param  mixed  $user
     * @param  int    $id
     * @param  array  $input
     * @return mixed
     */
    public function update($user, int $id, array $input);
}
