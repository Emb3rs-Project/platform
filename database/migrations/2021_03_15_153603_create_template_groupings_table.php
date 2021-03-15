<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemplateGroupingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('template_groupings', function (Blueprint $table) {
            $table->id();

            $table->integer('order')->unsigned()->default(0);
            $table->boolean('is_summary')->default(false);

            $table->foreignId('template_id');
            $table->foreignId('property_group_id');

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
        Schema::dropIfExists('template_groupings');
    }
}
