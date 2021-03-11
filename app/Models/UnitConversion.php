<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UnitConversion extends Model
{
    use HasFactory, SoftDeletes;

    public function from() : BelongsTo
    {
        return $this->belongsTo(Unit::class, 'from_id');
    }

    public function to(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'to_id');
    }
}
