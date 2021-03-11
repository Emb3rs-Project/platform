<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeoSegment extends Model
{
    use HasFactory,SoftDeletes;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
    ];

    // Table geo_segment_link
    public function links() : BelongsToMany
    {
        return $this->belongsToMany(
            Link::class,
            'geo_segment_link',
            'geo_segment_id',
            'link_id'
        );
    }
}
