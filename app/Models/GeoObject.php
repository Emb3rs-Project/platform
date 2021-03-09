<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeoObject extends Model
{
    use HasFactory, SoftDeletes;

    public function location() : BelongsTo
    {
        return $this->belongsTo(Location::class, 'geo_object_id');
    }
}
