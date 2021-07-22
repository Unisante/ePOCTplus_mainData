<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAlgIdAndVersionIdToHealthFacilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('health_facilities', function (Blueprint $table) {
            $table->bigInteger('version_id')->nullable();
            $table->bigInteger('algorithm_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('health_facilities', function (Blueprint $table) {
            $table->dropColumn('version_id');
            $table->dropColumn('algorithm_id');
        });
    }
}
