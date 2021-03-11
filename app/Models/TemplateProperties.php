<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TemplateProperties extends Model
{
    use HasFactory, SoftDeletes;

    // Table template_properties
    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class, 'template_id');
    }

    // Table template_properties
    public function property(): BelongsTo
    {
        return $this->belongsTo(Properties::class, 'property_id');
    }

    // Table template_properties
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'default_unit_id');
    }
}