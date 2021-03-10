<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TemplateProperties extends Model
{
    use HasFactory, SoftDeletes;


    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
}
