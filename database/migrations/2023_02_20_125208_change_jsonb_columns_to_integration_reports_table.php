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
            $table->longText('data')->nullable()->change();
            $table->longText('errors')->nullable()->change();
            $table->longText('output')->nullable()->change();
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
            $table->jsonb('data')->nullable()->change();
            $table->jsonb('errors')->nullable()->change();
            $table->jsonb('output')->nullable()->change();
        });
    }
};
