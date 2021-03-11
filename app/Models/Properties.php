<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Properties extends Model
{
    use HasFactory, SoftDeletes;

    // Table template_properties
    public function templateProperties(): HasMany
    {
        return $this->hasMany(TemplateProperties::class, 'property_id');
    }
}
