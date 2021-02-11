<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnsInCustomDiagnosis extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('custom_diagnoses', function(Blueprint $table) {
        $table->renameColumn('medal_c_id', 'label');
        $table->renameColumn('description', 'drugs');
        $table->integer('medical_case_id')->unsigned();
        $table->foreign('medical_case_id')->references('id')->on('medical_cases');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('custom_diagnoses', function(Blueprint $table) {
        $table->renameColumn('label', 'medal_c_id');
        $table->renameColumn('drugs', 'description');
        $table->dropColumn('medical_case_id');
      });
    }
}
