<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCloseAtToMedicalCase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
  public function up()
  {
    Schema::table('medical_cases', function (Blueprint $table) {
      $table->dateTime('closedAt')->nullable();
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
      $table->dropColumn('closedAt');
    });
  }
}
