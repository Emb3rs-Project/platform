<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TemplateGrouping extends Model
{
    /**
     * The Property Group that this Template Grouping belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function propertyGroup(): BelongsTo
    {
        return $this->belongsTo(PropertyGroup::class, 'property_group_id');
    }

    /**
     * The Template that this Template Grouping belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class, 'template_id');
    }

    /**
     * The Template Properties that this Template Grouping has.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function templateProperties(): HasMany
    {
        return $this->hasMany(TemplateProperty::class, 'grouping_id');
    }
}
