<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Instance extends Model
{
    use HasFactory, SoftDeletes;

    /**
    * The attributes that should be cast.
    *
    * @var array
    */
    protected $casts = [
        'values' => 'array',
    ];

    // Table instances
    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class, 'template_id');
    }

    // Table instances
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    // Table team_instance
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(
            Team::class,
            'team_instance',
            'instance_id',
            'team_id'
        );
    }
}
