<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMedicalCaseRedcapFlagToMedicalCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('medical_cases', function (Blueprint $table) {
          $table->boolean('mc_redcap_flag')->nullable()->default(false);
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
          $table->dropColumn('mc_redcap_flag');

        });
    }
}
