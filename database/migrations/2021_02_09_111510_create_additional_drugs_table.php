<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdditionalDrugsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('additional_drugs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('drug_id')->unsigned();
            $table->foreign('drug_id')->references('id')->on('drugs');
            $table->integer('medical_case_id')->unsigned();
            $table->foreign('medical_case_id')->references('id')->on('medical_cases');
            $table->integer('formulationSelected');
            $table->boolean('agreed');
            $table->integer('version_id')->unsigned();
            $table->foreign('version_id')->references('id')->on('versions');
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
        Schema::dropIfExists('additional_drugs');
    }
}
