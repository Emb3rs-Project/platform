<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSimulationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('simulations', function (Blueprint $table) {
            $table->id();

            $table->enum('status', ['NEW','IN PREPARATION', 'READY', 'ANALYSING', 'STOPPED', 'ERROR'])->default('NEW');
            $table->jsonb('targetData');

            $table->foreignId('project_id');
            $table->foreignId('target_id');
            $table->foreignId('simulation_type_id');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('simulations');
    }
}