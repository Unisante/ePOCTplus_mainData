<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangDrugReferenceDiagnosisIdtoDiagnosisReference extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('drug_references', function (Blueprint $table) {
          $table->dropForeign('drug_references_diagnosis_id_foreign');
          $table->foreign('diagnosis_id')->references('id')->on('diagnosis_references');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('drug_references', function (Blueprint $table) {
          // $table->dropForeign('drug_references_diagnosis_id_foreign');
          // $table->foreign('diagnosis_id')->references('id')->on('diagnoses');
        });
    }
}
