<?php

namespace App\Jobs;

use App\Contracts\Embers\Integration\StartsSimulations;
use App\Models\SimulationSession;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Manager\ManagerClient;
use Manager\StartSimulationRequest;

class StartSimulation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected SimulationSession $session;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SimulationSession $session)
    {
        $session->load(['simulation', 'simulation.simulationMetadata']);
        $this->session = $session;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        app(StartsSimulations::class)->run_simulation($this->session);
    }
}
