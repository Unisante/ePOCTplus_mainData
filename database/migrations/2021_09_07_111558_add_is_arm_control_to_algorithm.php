<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsArmControlToAlgorithm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('algorithms', function (Blueprint $table) {
        $table->boolean('is_arm_control')->nullable();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('algorithms', function (Blueprint $table) {
        $table->dropColumn('is_arm_control');
      });
    }
}
