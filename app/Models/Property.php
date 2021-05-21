<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
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

    // Table template_properties
    public function templateProperties(): HasMany
    {
        return $this->hasMany(TemplateProperty::class, 'property_id');
    }

    public function units()
    {
        return $this->belongsToMany(
            Unit::class,
            'unit_property',
            'property_id',
            'unit_id'
        );
    }
}
