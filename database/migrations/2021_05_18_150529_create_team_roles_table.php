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

            $table->unique(['team_id', 'role']);

            // {
            //     "permission": [
            //       "can create source",
            //       "can read source",
            //       "can update source",
            //       "can delete source",
            //       "can create sink",
            //       "can read sink",
            //       "can update sink",
            //       "can delete sink",
            //       "can create link",
            //       "can read link",
            //       "can update link",
            //       "can delete link",
            //       "can create project",
            //       "can read project",
            //       "can update project",
            //       "can delete project",
            //       "can create simulation",
            //       "can read simulation",
            //       "can update simulation",
            //       "can delete simulation"
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
