<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDetailsToDrugReferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('drug_references', function (Blueprint $table) {
            $table->integer('formulation_id')->unsigned()->nullable();
            $table->foreign('formulation_id')->references('id')->on('formulations');
            $table->boolean('additional')->nullable();
            $table->string('duration')->nullable();
            $table->boolean('agreed')->nullable()->change();
            $table->integer('formulationSelected')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('drug_references', function (Blueprint $table) {
            $table->removeColumn('formulation_id');
            $table->removeColumn('additional');
            $table->removeColumn('duration');
            $table->boolean('agreed')->nullable(false)->change();
            $table->integer('formulationSelected')->nullable(false)->change();
        });
    }
}
