<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManagementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('managements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('medal_c_id');
            $table->string('type');
            $table->integer('reference')->nullable();
            $table->string('label');
            $table->text('description');
            $table->integer('diagnosis_id')->unsigned();
            $table->foreign('diagnosis_id')->references('id')->on('diagnoses');
            $table->integer('custom_diagnosis_id')->unsigned();
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
        Schema::dropIfExists('managements');
    }
}
