<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAlgoIdToHfAccessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('health_facility_accesses', function (Blueprint $table) {
            $table->integer('medal_c_algorithm_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('health_facility_accesses', function (Blueprint $table) {
            $table->dropColumn("medal_c_algorithm_id");
        });
    }
}
