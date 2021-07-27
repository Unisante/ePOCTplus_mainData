<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddManagementColumnsToHealthFacilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('health_facilities', function (Blueprint $table) {
            $table->string('name');
            $table->string('country')->nullable();
            $table->string("area")->nullable();
            $table->string('local_data_ip')->nullable();
            $table->string('pin_code')->nullable();
            $table->bigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('health_facilities', function (Blueprint $table) {
            //
            $table->dropColumn('name');
            $table->dropColumn('country');
            $table->dropColumn("area");
            $table->dropColumn('local_data_ip');
            $table->dropColumn('pin_code');
            $table->dropColumn('user_id');
        });
    }
}
