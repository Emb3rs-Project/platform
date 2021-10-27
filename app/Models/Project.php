<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Laravel\Scout\Searchable;

class Project extends Model
{
    use SoftDeletes, Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'location_id'
    ];

    /**
     * The Locations that this Project has.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function locations(): HasMany
    {
        return $this->hasMany(Location::class, 'project_id');
    }

    /**
     * The Location that this Project belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    /**
     * The Simulations that this Project has.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function simulations(): HasMany
    {
        return $this->hasMany(Simulation::class, 'project_id');
    }

    /**
     * The Teams that this Project belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(
            Team::class,
            'team_project',
            'project_id',
            'team_id'
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

        return Arr::only($array, ['id', 'name', 'description']);
    }
}
