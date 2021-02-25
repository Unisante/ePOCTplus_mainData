<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChnageColumnDataTypesToFormulations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('formulations', function (Blueprint $table) {
          $table->float('minimal_dose_per_kg')->change();
          $table->float('maximal_dose_per_kg')->change();
          $table->float('maximal_dose')->change();
          $table->float('liquid_concentration')->nullable()->change();
          $table->float('dose_form')->change();
          $table->float('doses_per_day')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('formulations', function (Blueprint $table) {
          // $table->dropColumn('minimal_dose_per_kg');
          // $table->dropColumn('maximal_dose_per_kg');
          // $table->dropColumn('maximal_dose');
          // $table->dropColumn('liquid_concentration');
          // $table->dropColumn('dose_form');
          // $table->dropColumn('doses_per_day');
        });
    }
}
