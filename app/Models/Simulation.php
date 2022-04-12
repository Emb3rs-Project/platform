<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Laravel\Scout\Searchable;

class Simulation extends Model
{
    use SoftDeletes, Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'status',
        'extra',
        'project_id',
        'target_id',
        'simulation_type_id',
        'name',
        'simulation_metadata_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'extra' => 'array',
    ];

    /**
     * The Project that this Simulation belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    /**
     * The Target that this Simulation belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function target(): BelongsTo
    {
        return $this->belongsTo(Target::class, 'target_id');
    }

    /**
     * The Simulation Type that this Simulation belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function simulationType(): BelongsTo
    {
        return $this->belongsTo(SimulationType::class, 'simulation_type_id');
    }

    /**
     * The Simulation Metadata that this Simulation belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function simulationMetadata(): BelongsTo
    {
        return $this->belongsTo(SimulationMetadata::class, 'simulation_metadata_id');
    }

    /**
     * The Simulation Results that this Simulation has.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function simulationResults(): HasMany
    {
        return $this->hasMany(SimulationResult::class, 'simulation_id');
    }

    /**
     * The Integration Results that this Simulation has.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function integrationReports(): HasMany
    {
        return $this->hasMany(IntegrationReport::class, 'simulation_id');
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray(): array
    {
        $array = $this->toArray();

        return Arr::only($array, ['id', 'status', 'project']);
    }
}
