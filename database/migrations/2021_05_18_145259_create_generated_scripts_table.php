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
        Schema::create('generated_scripts', function (Blueprint $table) {
            $table->id();

            $table->string('name', 100)->nullable()->default('NO NAME');
            $table->string('path', 250)->nullable();

            $table->foreignId('instance_id');

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
        Schema::dropIfExists('generated_scripts');
    }
};
