<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TemplateGrouping extends Model
{
    use HasFactory;

    public function propertyGroup() : BelongsTo
    {
        return $this->belongsTo(PropertyGroups::class, 'property_group_id');
    }

    public function template() : BelongsTo
    {
        return $this->belongsTo(Template::class, 'template_id');
    }

    public function templateProperties()
    {
        return $this->hasMany(TemplateProperties::class, 'grouping_id');
    }
}
