<?php

use App\Models\Challenge;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('challenge_user', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Challenge::class)->nullable();
            $table->foreignIdFor(User::class)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('challenge_user');
    }
};
