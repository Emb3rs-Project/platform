<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddProcessEnumToCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->renameColumn('type', 'old_type');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->enum('type', ['sink', 'source', 'equipment', 'process'])->nullable();
        });

        DB::statement('UPDATE categories SET type = old_type');

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('old_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->renameColumn('type', 'old_type');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->enum('type', ['sink', 'source', 'equipment'])->nullable();
        });

        DB::statement('UPDATE categories SET type = old_type');

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('old_type');
        });
    }
}
