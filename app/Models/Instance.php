<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Actionable;
use Laravel\Scout\Searchable;

class Instance extends Model
{
    use SoftDeletes, Actionable, Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'values',
        'template_id',
        'location_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'values' => 'array',
    ];

    /**
     * The Template that this Instance belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class, 'template_id');
    }

    /**
     * The Location that this Instance belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    /**
     * The Teams that this Instance belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(
            Team::class,
            'team_instance',
            'instance_id',
            'team_id'
        );
    }

    /**
     * The Instance Group that this Instance belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function grouping(): BelongsToMany
    {
        return $this->belongsToMany(
            Instance::class,
            'instance_grouping',
            'instance_id',
            'parent_instance_id'
        );
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray(): array
    {
        $array = $this->toArray();

        return Arr::only($array, ['id', 'name']);
    }

    public function getInstanceData(): array
    {
        $instanceData = $this->values;
        $instanceData["id"] = $this->id;
        $instanceData["type"] = $this->template->category->type;
        $instanceData["location"] = $this->location->data["center"];

        return $instanceData;
    }
}
