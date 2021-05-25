<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Nova\Actions\Actionable;

class Instance extends Model
{
    use SoftDeletes;
    use Actionable;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'values' => 'array',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'values',
        'template_id',
        'location_id'
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

    public function grouping(): BelongsToMany
    {
        return $this->belongsToMany(
            Instance::class,
            'instance_grouping',
            'instance_id',
            'parent_instance_id'
        );
    }
}
