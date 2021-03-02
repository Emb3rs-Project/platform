<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSimulationConstraintInstancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('simulation_constraint_instances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('simulation_manager_id')->constrained();
            $table->foreignId('simulation_constraint_id')->constrained();
            $table->foreignId('unit_id')->constrained();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('simulation_c_onstraint_instances');
    }
}
