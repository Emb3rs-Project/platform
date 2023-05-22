<?php

namespace App\Models;

use App\Events\Embers\SimulationUpdate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Laravel\Scout\Searchable;

class Simulation extends Model
{
    use SoftDeletes, Searchable;

    public const NEW = 'NEW';
    public const IN_PREPARATION = 'IN PREPARATION';
    public const RUNNING = 'RUNNING';
    public const COMPLETED = 'COMPLETED';
    public const ERROR = 'ERROR';
    public const INPUT_NEEDED = 'WAITING FOR USER';

    public const PROGRESS = [
        'demo-simulation' => [
            'simulator-simulation-started' => 5,
            'cf-module-convert-sink' => 10,
            'cf-module-convert-source' => 15,
            'gis-module-create-network' => 30,
            'gis-module-optimize-network' => 45,
            'teo-module-buildmodel' => 60,
            'market-module-long-term' => 70,
            'business-module-feasability' => 85,
            'simulator-simulation-finished' => 100
        ],
        'convert-orc' => [
            'simulator-simulation-started' => 5,
            'cf-module-convert-orc' => 50,
            'simulator-simulation-finished' => 100
        ]
    ];

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
        'simulation_metadata_id',
        'requested_by'
    ];

    protected $appends = [
        'progress'
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

    public function simulationSessions(): HasMany
    {
        return $this->hasMany(SimulationSession::class, 'simulation_id');
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

    /**
     * Change Status
     * @param $status
     * @param bool $shouldSave
     */
    public function changeStatusTo($status, $shouldSave = true): void
    {
        $this->status = $status;

        if ($shouldSave) {
            $this->save();
            broadcast(new SimulationUpdate($this->id));
        }
    }

    /**
     * @return int
     */
    public function getProgressAttribute()
    {
        if ($this->status === 'RUNNING') {
            $this->load('simulationSessions', 'simulationMetadata');
            $session = $this->simulationSessions->last();
            if ($session) {
                $report = IntegrationReport::where('simulation_uuid', 'like', $session->simulation_uuid)
                    ->orderBy('created_at', 'desc')->orderBy('id', 'desc')
                    ->latest()->first();
                if ($report) {
                    $slug = \Str::slug($report->module . '-' . $report->function);
                    $data = $this->simulationMetadata->data;
                    $keySlug = \Str::slug($data['identifier']);
                    return self::PROGRESS[$keySlug][$slug] ?? 100;
                }
                return 0;
            }
        }
        return 0;
    }

    public function createStepFor($sessionUUID, $module, $function, $data = [], $output= [], $errors=null) {
        IntegrationReport::create([
            'module' => $module,
            'function' => $function,
            'data' => $data,
            'output' => json_encode($output),
            'errors' => $errors,
            'simulation_uuid' => $sessionUUID,
            'step_uuid' => $sessionUUID,
            'simulation_id' => $this->id
        ]);

    }

    public function scopeOnInstitutionFor($query, $user) {
        $teamProjects = $user->team?->projects->pluck('id')->toArray() ?? [];
        return $query->whereIn('project_id', $teamProjects);
    }
}
