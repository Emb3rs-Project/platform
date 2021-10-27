<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UnitConversion extends Model
{
    use SoftDeletes;

    /**
     * The Unit From that this Unit Conversion belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function from(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'from_id');
    }

    /**
     * The Unit To that this Unit Conversion belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function to(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'to_id');
    }
}
