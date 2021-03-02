<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeSimulationManagersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('simulation_managers', function (Blueprint $table) {
            $table->foreignId('simulation_target_instance_id')->constrained();
            $table->foreignId('simulation_type_instance_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('simulation_managers', function (Blueprint $table) {
            $table->dropForeign('simulation_target_instance_id');
            $table->dropForeign('simulation_type_instance_id');
        });
    }
}
