<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('description')->nullable();
            $table->enum('type', ['point','circle','polygon']);
            $table->jsonb('data');
            /**
             * {
             *      "data": {
             *          "coordinates": [
             *              38.75261070835031,
             *              -9.303359985351564
             *          ],
             *          "radius": 23
             *      }
             * }
             *
             * {
             *      "data": {
             *          "coordinates": [
             *              38.75261070835031,
             *              -9.303359985351564
             *          ],
             *      }
             * }
             *
             * {
             *      "data": {
             *          "coordinates": [
             *              [
             *                  [
             *                      39.85915479295669,
             *                      21.7913818359375
             *                  ],
             *                  [
             *                      39.198205348894795,
             *                      21.423339843749996
             *                  ],
             *                  [
             *                      39.85915479295669,
             *                      21.4617919921875
             *                  ],
             *                  [
             *                      39.1854331703021,
             *                      22.21435546875
             *                  ],
             *                  [
             *                      39.40224434029275,
             *                      22.6593017578125
             *                  ],
             *                  [
             *                      39.614152077002664,
             *                      22.587890625
             *                  ],
             *                  [
             *                      39.85915479295669,
             *                      21.7913818359375
             *                  ]
             *              ]
             *          ]
             *      }
             * }
             */

            $table->foreignId('project_id')->nullable();

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
        Schema::dropIfExists('locations');
    }
}
