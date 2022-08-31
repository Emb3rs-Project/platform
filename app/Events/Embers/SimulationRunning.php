<?php

namespace App\Events\Embers;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithBroadcasting;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class SimulationRunning implements ShouldBroadcastNow
{
    use InteractsWithBroadcasting;

    public $data = [];

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->data = [
            'description' => 'Simulation is Running. You will be notified when the simulation is finished',
            'id' => $id
        ];
        $this->broadcastVia('pusher');
    }

    public function broadcastOn()
    {
        return new Channel('my-simulations');
    }

    public function broadcastAs()
    {
        return 'simulation-run';
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable): array
    {
        return $this->data;
    }
}
