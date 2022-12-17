<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ChallengeUser extends Pivot
{

    protected $table = 'challenge_user';


    /**
     * @return BelongsTo
     */
    public function challenge(): BelongsTo
    {
        return $this->belongsTo(Challenge::class);
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sessions(): BelongsToMany
    {
        return $this->belongsToMany(SimulationSession::class, 'challenge_session', 'challenge_user_id', 'simulation_session_id')
            ->using(ChallengeSession::class)
            ->withPivot(['challenge_id']);
    }

}
