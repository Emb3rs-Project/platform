<?php

namespace App\Notifications;

use App\Models\TeamRole;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MemberInvited extends Notification
{
    use Queueable;

    private mixed $inviter;
    private mixed $team;
    private $tag = 'tag'; // Refference the tag for mthe db here

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($inviter, $team)
    {
        $this->inviter = $inviter;
        $this->team = $team;
        // TODO: add tag
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
        return [
            'inviter' => $this->inviter,
            'team' => $this->team,
            'tag' => $this->tag
        ];
    }

    /**
     * Get the database representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'inviter' => $this->inviter,
            'team' => $this->team,
            'tag' => $this->tag
        ];
    }
}
