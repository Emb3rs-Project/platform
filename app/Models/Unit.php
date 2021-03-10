<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use HasFactory, SoftDeletes;

    public function coversions(): HasMany
    {
        return $this->hasMany(UnitConversion::class, 'from_id');
    }

    public function templateProperties(): HasMany
    {
        return $this->hasMany(TemplateProperties::class, 'default_unit_id');
    }
}
