<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiagnosisReferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diagnosis_references', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('agreed');
            $table->boolean('proposed_additional');
            $table->integer('diagnosis_id')->unsigned();
            $table->integer('medical_case_id')->unsigned();
            $table->foreign('diagnosis_id')->references('id')->on('diagnoses');
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
        Schema::dropIfExists('diagnosis_references');
    }
}
