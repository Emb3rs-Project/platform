<?php

use App\Models\ChallengeUser;
use App\Models\SimulationSession;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('challenge_session', function (Blueprint $table) {
            $table->id();
            $table->foreignId('simulation_session_id')->nullable();
            $table->foreignId('challenge_user_id')->nullable();
            $table->foreignId('challenge_id')->nullable();
            $table->string('goal_value')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('challenge_session');
    }
};
