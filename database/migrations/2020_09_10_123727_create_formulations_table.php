<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormulationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formulations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('medication_form');
            $table->string('administration_route_name');
            $table->integer('liquid_concentration')->nullable();
            $table->integer('dose_form');
            $table->integer('unique_dose')->nullable();
            $table->boolean('by_age')->nullable();
            $table->integer('minimal_dose_per_kg');
            $table->integer('maximal_dose_per_kg');
            $table->integer('maximal_dose');
            $table->text('description')->nullable();
            $table->integer('doses_per_day');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('formulations');
    }
}
