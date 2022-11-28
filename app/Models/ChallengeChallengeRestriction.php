<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ChallengeChallengeRestriction extends Pivot
{

    /**
     * string
     */
    protected $table = 'challenge_challenge_restriction';

    /**
     * @var bool
     */
    public $incrementing = true;

    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }

    public function restriction()
    {
        return $this->belongsTo(ChallengeRestriction::class);
    }
}
