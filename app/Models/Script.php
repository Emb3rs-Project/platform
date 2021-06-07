<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Script extends Model
{
    use HasFactory;


    // Table team_link
    public function templates(): BelongsToMany
    {
        return $this->belongsToMany(
            Template::class,
            'scripts_templates',
            'script_id',
            'template_id'
        );
    }

    // Table team_link
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            Category::class,
            'scripts_categories',
            'script_id',
            'category_id'
        );
    }
}
