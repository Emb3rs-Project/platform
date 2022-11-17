<?php

use App\Models\ChallengeGoal;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('challenges', function (Blueprint $table) {
            $table->foreignIdFor(ChallengeGoal::class, 'goal_id')->nullable();
        });
    }

    public function down()
    {
        Schema::table('challenges', function (Blueprint $table) {
            $table->dropForeignIdFor(ChallengeGoal::class, 'goal_id');
        });
    }
};
