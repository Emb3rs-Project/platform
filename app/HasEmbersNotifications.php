<?php

namespace App;

use Illuminate\Support\Str;

trait HasEmbersNotifications
{
    protected mixed $from;
    protected string $type;
    protected string $description;
    protected array $tags;

    /**
     * Get the database representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toSave()
    {
        return [
            'from' => $this->from,
            'type' => $this->type,
            'description' => $this->description,
            'tags' => $this->tags,
        ];
    }
}
