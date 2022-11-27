<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChallengeGoal extends Model
{
    protected $fillable = [
        'name',
        'description',
        'output'
    ];
}
