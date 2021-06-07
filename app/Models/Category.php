<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use  SoftDeletes;

    // Table categories
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Table templates
    public function templates(): HasMany
    {
        return $this->hasMany(Template::class, 'category_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function scripts(): BelongsToMany
    {
        return $this->belongsToMany(
            Script::class,
            'scripts_categories',
            'category_id',
            'script_id',
        );
    }
}
