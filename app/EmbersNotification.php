<?php

namespace App;

use Illuminate\Notifications\Notification;

class EmbersNotification extends Notification
{
    /**
     * The sender of the notification.
     *
     * @var mixed
     */
    protected mixed $from;

    /**
     * The type of the notification.
     *
     * @var string
     */
    protected string $type;

    /**
     * The description of the notification.
     *
     * @var string|null
     */
    protected string|null $description;

    /**
     * The tags of the notification.
     *
     * @var  array|null
     */
    protected array|null $tags;

    /**
     * Get the representation of the notification.
     *
     * This method is intented to be used inside every method that Laravel
     * notifications support (toDatabase, toArray, toBroadcast, etc.)
     *
     * @return array
     */
    public function share(): array
    {
        return [
            'from' => $this->from,
            'type' => $this->type,
            'description' => $this->description,
            'tags' => $this->tags,
        ];
    }
}
