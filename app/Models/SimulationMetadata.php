<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SimulationMetadata extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
    ];

    public function simulations(): HasMany
    {
        return $this->hasMany(Simulation::class, 'simulation_metadata_id');
    }

    public function templates()
    {
        return $this->hasMany(Template::class, 'simulation_metadata_id');
    }

    /**
     * The Integration Results that this Simulation Metadata has.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function integrationReports(): HasMany
    {
        return $this->hasMany(IntegrationReport::class, 'simulation__metadata_id');
    }

    public function modules()
    {
        return [];
    }
}
