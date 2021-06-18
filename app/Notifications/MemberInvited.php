<?php

namespace App\Notifications;

use App\EmbersNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class MemberInvited extends EmbersNotification
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
    public function __construct($inviter, $team, ?string $description)
    {
        Log::info($description);
        $this->from = $inviter;
        $this->type = 'invitation';
        $this->description = $description;
        $this->tags = ['invite'];

        $this->team = $team;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [];
    }

    /**
     * Get the database representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        // ! Be careful not to override the properties from the parent class
        return array_merge(parent::toSave(), [
            'team' => $this->team,
        ]);
    }
}
