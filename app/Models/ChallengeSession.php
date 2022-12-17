<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ChallengeSession extends Pivot
{

    protected $table = 'challenge_session';

    /**
     * @return BelongsTo
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(SimulationSession::class);
    }

    /**
     * @return BelongsTo
     */
    public function challengeUser(): BelongsTo
    {
        return $this->belongsTo(ChallengeUser::class);
    }
}
