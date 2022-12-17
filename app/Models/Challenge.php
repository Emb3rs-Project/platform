<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Challenge extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'goal_id',
        'project_id',
    ];

    /**
     * @return BelongsToMany
     */
    public function restrictions(): BelongsToMany
    {
        return $this->belongsToMany(ChallengeRestriction::class)
            ->using(ChallengeChallengeRestriction::class)
            ->withPivot(['id', 'value']);
    }

    /**
     * @return BelongsTo
     */

    public function goal(): BelongsTo
    {
        return $this->belongsTo(ChallengeGoal::class);
    }

    /**
     * @return BelongsToMany
     */
    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->using(ChallengeUser::class)
            ->withPivot(['id', 'created_at']);
    }

    public function project(): belongsTo
    {
        return $this->belongsTo(Project::class);
    }

}
