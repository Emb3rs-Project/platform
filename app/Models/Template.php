<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Template extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const SIMPLE_SINK = 14;
    public const SIMPLE_SOURCE = 14;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'values' => 'array',
    ];

    /**
     * The Category that this Template belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * The Instances that this Template has.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function instances(): HasMany
    {
        return $this->hasMany(Instance::class, 'template_id');
    }

    /**
     * The Template Properties that this Template has.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function templateProperties(): HasMany
    {
        return $this->hasMany(TemplateProperty::class, 'template_id');
    }

    /**
     * The Template Grouping that this Template has.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function templateGrouping(): HasMany
    {
        return $this->hasMany(TemplateGrouping::class, 'property_group_id');
    }

    /**
     * The Simulation Metadata that this Template Triggers when creating an instance.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function triggers(): BelongsTo
    {
        return $this->belongsTo(SimulationMetadata::class, 'simulation_metadata_id');
    }
}
