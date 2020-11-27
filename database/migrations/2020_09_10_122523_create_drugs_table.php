<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDrugsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drugs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('medal_c_id');
            $table->string('type');
            $table->integer('reference')->nullable();
            $table->string('label');
            $table->text('description');
            $table->boolean('is_anti_malarial');
            $table->boolean('is_antibiotic');
            $table->integer('formulationSelected')->nullable();
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
        Schema::dropIfExists('drugs');
    }
}
