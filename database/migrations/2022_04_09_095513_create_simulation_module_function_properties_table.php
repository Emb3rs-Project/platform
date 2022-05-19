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
        Schema::create('simulation_module_function_properties', function (Blueprint $table) {
            $table->id();

            $table->boolean('required')->default(false);
            $table->boolean('advanced')->default(false);
            $table->integer('order')->unsigned();
            $table->string('default_value')->nullable();

            $table->foreignId("simulation_module_function_id");
            $table->foreignId("property_id");
            $table->foreignId("unit_id");

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
        Schema::dropIfExists('simulation_module_function_properties');
    }
};
