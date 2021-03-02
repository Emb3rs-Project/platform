<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UnitConversion extends Model
{
    use HasFactory, SoftDeletes;


    /**
     * Target unit for conversion
     */
    public function targetUnit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'target_unit_id');
    }

    /**
     * Source unit for conversion
     */
    public function sourceUnit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'source_unit_id');
    }
}
