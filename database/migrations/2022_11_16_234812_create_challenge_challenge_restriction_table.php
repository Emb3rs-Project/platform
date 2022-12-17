<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('challenge_challenge_restriction', function (Blueprint $table) {
            $table->id();
            $table->foreignId('challenge_id')->nullable();
            $table->foreignId('challenge_restriction_id')->nullable();
            $table->text('value')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('challenge_challenge_restriction');
    }
};
