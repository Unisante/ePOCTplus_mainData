<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('medical_case_id')->unsigned();
          $table->uuid('medal_c_id')->unsigned();
          $table->string('step')->nullable();
          $table->string('clinician')->nullable();
          $table->string('mac_address')->nullable();
          $table->foreign('medical_case_id')->references('id')->on('medical_cases');
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
        Schema::dropIfExists('activities');
    }
}
