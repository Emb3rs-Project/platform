<?php

namespace App\Notifications\Embers;

use App\EmbersNotification;
use App\Models\Team;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class MemberAdded extends EmbersNotification implements ShouldQueue
{
    use Queueable;

    /**
     * The team that the user is invited to.
     *
     * @var mixed
     */
    private mixed $team;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $owner, Team $team, ?string $description = null)
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

        $this->type = 'inclusion';
        $this->tags = ['Institutions'];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable): array
    {
        // ! Be careful not to override the properties from the parent class
        return array_merge(parent::share(), [
            'team' => $this->team,
        ]);
    }
}
