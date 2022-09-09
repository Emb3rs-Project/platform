<?php

namespace App\Notifications\Embers;

use App\EmbersNotification;
use App\Models\Team;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SimulationNotification extends EmbersNotification implements ShouldQueue
{
    use Queueable;

    private mixed $team;
    private $contentId;

    /**
     * Create a new notification object.
     *
     * @return void
     */
    public function __construct(User $owner, Team $team, ?array $tag = [], ?string $description = null, $contentId)
    {
        $this->from = $owner->only([
            'id',
            'name',
            'email',
            'profile_photo_url',
            'profile_photo_path'
        ]);
        $this->team = $team->only(['id', 'name']);
        $this->description = $description;
        $this->contentId = $contentId;

        $this->type = 'simulation';
        $this->tags = $tag;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable): array
    {
        return array_merge(parent::share(), [
            'team'          => $this->team,
            'contentId'     => $this->contentId
        ]);
    }
}
