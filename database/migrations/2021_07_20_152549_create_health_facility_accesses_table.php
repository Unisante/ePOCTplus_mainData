<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHealthFacilityAccessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('health_facility_accesses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->boolean('access');
            $table->dateTime('end_date')->nullable();
            $table->bigInteger('creator_version_id');
            $table->string('version_name');
            $table->integer('medal_r_json_version');
            $table->boolean('is_arm_control');
            $table->bigInteger('health_facility_id');
            $table->foreign('health_facility_id')->references('id')->on('health_facilities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('health_facility_accesses');
    }
}
