<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnmanagementsToReferences extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('management_references', function (Blueprint $table) {
          $table->integer('management_id')->unsigned();
          $table->foreign('management_id')->references('id')->on('managements');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('management_references', function (Blueprint $table) {
          $table->dropColumn('management_id');
        });
    }
}
