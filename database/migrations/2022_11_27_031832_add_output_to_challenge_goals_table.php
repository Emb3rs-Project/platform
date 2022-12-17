<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('challenge_goals', function (Blueprint $table) {
            $table->string('output')->nullable()->after('description');
        });
    }

    public function down()
    {
        Schema::table('challenge_goals', function (Blueprint $table) {
            $table->dropColumn('output');
        });
    }
};
