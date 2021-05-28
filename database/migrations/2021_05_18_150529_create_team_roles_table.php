<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_roles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('team_id');
            $table->string('role');

            $table->jsonb('permissions')->nullable(); // array of permission names

            // {
            //     "permissions": [
            //       "can create sources",
            //       "can read sources",
            //       "can update sources",
            //       "can delete sources",
            //       "can create sinks",
            //       "can read sinks",
            //       "can update sinks",
            //       "can delete sinks",
            //       "can create links",
            //       "can read links",
            //       "can update links",
            //       "can delete links",
            //       "can create projects",
            //       "can read projects",
            //       "can update projects",
            //       "can delete projects",
            //       "can create simulations",
            //       "can read simulations",
            //       "can update simulations",
            //       "can delete simulations"
            //     ]
            // }

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
        Schema::dropIfExists('team_roles');
    }
}
