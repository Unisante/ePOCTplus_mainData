<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNullableToManagements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('managements', function (Blueprint $table) {
          $table->text('description')->nullable()->change();
          $table->integer('diagnosis_id')->unsigned()->nullable()->change();
          $table->integer('custom_diagnosis_id')->unsigned()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('managements', function (Blueprint $table) {
          // $table->dropColumn('description');
          // $table->dropColumn('diagnosis_id');
          // $table->dropColumn('custom_diagnosis_id');
        });
    }
}
