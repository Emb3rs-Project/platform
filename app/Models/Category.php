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
    use SoftDeletes;

    /**
     * The Categories that this Category has as its parent.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * The Categories that this Category has as its children.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * The Templates that this Category has.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function templates(): HasMany
    {
        return $this->hasMany(Template::class, 'category_id');
    }

    /**
     * The Scripts that this Category belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
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
