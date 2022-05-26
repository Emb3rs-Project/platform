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
        Schema::table('integration_reports', function (Blueprint $table) {
            $table->string("module");
            $table->string("function");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('integration_reports', function (Blueprint $table) {
            $table->dropColumn("module");
            $table->dropColumn("function");
        });
    }
};
