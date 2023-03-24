<?php

namespace App\Console\Commands;

use App\Models\Simulation;
use Illuminate\Console\Command;

class DuplicateSimulation extends Command
{
    protected $signature = 'simulation:duplicate {simulationId} {NumCopy=1}';
    protected $description = 'Duplicate a simulation.';

    public function handle()
    {
        $links = [];

        $simulation = Simulation::findOrFail($this->argument('simulationId'));

        for ($i = 1; $i <= $this->argument('NumCopy'); $i ++) {
            $newSimulation = $simulation->replicate();
            $newSimulation->name = $simulation->name . ' - Copy (' . $i . ')';
            $newSimulation->save();

            $url = url('/projects/' . $newSimulation->project_id . '/simulations/' . $newSimulation->id);
            $links[] = $url;
        }

        $this->info('The following links have been generated:');
        foreach ($links as $link) {
            $this->line($link);
        }
    }
}

