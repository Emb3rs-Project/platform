<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('challenge_restrictions', function (Blueprint $table) {
            $table->string('unit')->nullable();
        });
    }

    public function down()
    {
        Schema::table('challenge_restrictions', function (Blueprint $table) {
            $table->dropColumn('unit');
        });
    }
};
