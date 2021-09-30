<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Simulation extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status',
        'targetData',
        'project_id',
        'target_id',
        'simulation_type_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'targetData' => 'array',
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
}
