<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Link extends Model
{
    use HasFactory, SoftDeletes;


    public function segments() : BelongsToMany
    {
        return $this->belongsToMany(
            GeoSegment::class,
            'geo_segment_link',
            'link_id',
            'geo_segment_id'
        );
    }
}
