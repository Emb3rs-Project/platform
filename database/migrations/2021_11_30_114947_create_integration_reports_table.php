<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIntegrationReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('integration_reports', function (Blueprint $table) {
            $table->id();

            $table->foreignId('simulation_metadata_id');
            $table->foreignId('simulation_id')->nullable();

            $table->jsonb('data')->nullable();
            $table->enum('type', ['simulation', 'characterization']);

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
        Schema::dropIfExists('integration_reports');
    }
}
