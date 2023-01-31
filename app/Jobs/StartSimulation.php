<?php

namespace App\Jobs;

use App\Actions\Embers\Integration\MarketShortTermSimulation;
use App\Actions\Embers\Integration\UpdateSimulation;
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
    protected $update = false;
    protected $payload = [];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SimulationSession $session, $update = false , $payload = [])
    {
        $session->load(['simulation', 'simulation.simulationMetadata']);
        $this->session = $session;
        $this->update  = $update;
        $this->payload = $payload;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $simulation = $this->session->simulation;

        if($simulation->simulationMetadata->data['type'] === 'standalone') {
            (new MarketShortTermSimulation())->run_simulation($this->session);
        } else if ($this->update){
            (new UpdateSimulation())->run_simulation($this->session, $this->payload);
        } else {
            app(StartsSimulations::class)->run_simulation($this->session);
        }
    }
}
