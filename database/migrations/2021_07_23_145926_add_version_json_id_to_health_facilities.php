<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVersionJsonIdToHealthFacilities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('health_facilities', function (Blueprint $table) {
            $table->bigInteger('version_json_id')->nullable();
            $table->foreign('version_json_id')->references('id')->on('version_jsons')->onDelete('set null');
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
            $table->dropColumn('version_json_id');
        });
    }
}
