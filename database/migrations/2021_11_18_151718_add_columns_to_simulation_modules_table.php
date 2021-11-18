<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToSimulationModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('simulation_modules', function (Blueprint $table) {
            $table->string('publish_channel')->nullable();
            $table->string('job_channel')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('simulation_modules', function (Blueprint $table) {
            $table->dropColumn('publish_channel');
            $table->dropColumn('job_channel');
        });
    }
}
