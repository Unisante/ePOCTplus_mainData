<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsConsentAndIsEligibleToMedicalCases extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('medical_cases', function (Blueprint $table) {
          $table->boolean('consent')->nullable();
          $table->boolean('isEligible')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('medical_cases', function (Blueprint $table) {
          $table->dropColumn('consent');
          $table->dropColumn('isEligible');
        });
    }
}
