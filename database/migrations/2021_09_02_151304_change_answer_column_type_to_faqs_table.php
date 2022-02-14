<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('f_a_q_s', function (Blueprint $table) {
            $table->renameColumn('answer', 'old_answer');
        });

        Schema::table('f_a_q_s', function (Blueprint $table) {
            $table->text('answer')->nullable();
        });

        DB::statement('UPDATE f_a_q_s SET answer = old_answer');

        Schema::table('f_a_q_s', function (Blueprint $table) {
            $table->dropColumn('old_answer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('f_a_q_s', function (Blueprint $table) {
            $table->renameColumn('answer', 'old_answer');
        });

        Schema::table('f_a_q_s', function (Blueprint $table) {
            $table->string('answer')->nullable();
        });

        DB::statement('UPDATE f_a_q_s SET answer = old_answer');

        Schema::table('f_a_q_s', function (Blueprint $table) {
            $table->dropColumn('old_answer');
        });
    }
};
