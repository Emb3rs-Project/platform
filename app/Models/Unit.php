<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use  SoftDeletes;

    // Table unit_conversions
    public function unitCoversionsFrom(): HasMany
    {
        return $this->hasMany(UnitConversion::class, 'from_id');
    }

    // Table unit_conversions
    public function unitCoversionsTo(): HasMany
    {
        return $this->hasMany(UnitConversion::class, 'to_id');
    }

    // Table template_properties
    public function templateProperties(): HasMany
    {
        return $this->hasMany(TemplateProperty::class, 'default_unit_id');
    }

    // Table targets
    public function targets(): HasMany
    {
        return $this->hasMany(Target::class, 'default_unit_id');
    }

    // Table simulation_types
    public function simulationTypes(): HasMany
    {
        return $this->hasMany(SimulationType::class, 'default_unit_id');
    }


    public function properties()
    {
        return $this->belongsToMany(
            Property::class,
            'unit_property',
            'unit_id',
            'property_id'
        );
    }
}
