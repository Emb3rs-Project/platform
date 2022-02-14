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
        Schema::create('simulation_module_functions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('simulation_module_id');

            $table->string('name');
            $table->string('label');
            $table->text('description')->nullable();

            $table->json('data');

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
        Schema::dropIfExists('simulation_module_functions');
    }
};
