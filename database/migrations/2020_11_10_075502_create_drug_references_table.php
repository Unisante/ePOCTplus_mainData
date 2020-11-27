<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDrugReferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drug_references', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('drug_id')->unsigned();
            $table->foreign('drug_id')->references('id')->on('drugs');
            $table->integer('diagnosis_id')->unsigned();
            $table->foreign('diagnosis_id')->references('id')->on('diagnoses');
            $table->boolean('agreed');
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
        Schema::dropIfExists('drug_references');
    }
}
