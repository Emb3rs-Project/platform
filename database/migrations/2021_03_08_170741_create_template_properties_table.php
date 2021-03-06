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
        Schema::create('template_properties', function (Blueprint $table) {
            $table->id();

            $table->boolean('required')->default(true);

            $table->foreignId('template_id');
            $table->foreignId('property_id');
            $table->foreignId('default_unit_id');

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
        Schema::dropIfExists('template_properties');
    }
};
