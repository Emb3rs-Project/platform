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
    use  SoftDeletes;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'values' => 'array',
    ];

    // Table templates
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // Table instances
    public function instances(): HasMany
    {
        return $this->hasMany(Instance::class, 'template_id');
    }

    // Table template_properties
    public function templateProperties(): HasMany
    {
        return $this->hasMany(TemplateProperty::class, 'template_id');
    }

    public function templateGrouping()
    {
        return $this->hasMany(TemplateGrouping::class, 'property_group_id');
    }
}
