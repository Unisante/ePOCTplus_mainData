<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeUnusedFieldsUnableToHealthFacilities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('health_facilities', function (Blueprint $table) {
            $table->string('facility_name')->nullable()->change();
            $table->integer('group_id')->nullable()->change();
            $table->integer('user_id')->nullable()->change();
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
            $table->string('facility_name')->nullable(false)->change();
            $table->integer('group_id')->nullable(false)->change();
            $table->integer('user_id')->nullable(false)->change();
        });
    }
}
