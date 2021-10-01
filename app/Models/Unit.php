<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use SoftDeletes;

    /**
     * The Unit Coversions From that this Unit has.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function unitCoversionsFrom(): HasMany
    {
        return $this->hasMany(UnitConversion::class, 'from_id');
    }

    /**
     * The Unit Coversions To that this Unit has.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function unitCoversionsTo(): HasMany
    {
        return $this->hasMany(UnitConversion::class, 'to_id');
    }

    /**
     * The Template Properties that this Unit has.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function templateProperties(): HasMany
    {
        return $this->hasMany(TemplateProperty::class, 'default_unit_id');
    }

    /**
     * The Targets that this Unit has.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function targets(): HasMany
    {
        return $this->hasMany(Target::class, 'default_unit_id');
    }

    /**
     * The Simulation Types that this Unit has.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function simulationTypes(): HasMany
    {
        return $this->hasMany(SimulationType::class, 'default_unit_id');
    }

    /**
     * The Properties that this Unit belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function properties(): BelongsToMany
    {
        return $this->belongsToMany(
            Property::class,
            'unit_property',
            'unit_id',
            'property_id'
        );
    }
}
