<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedicalCaseAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medical_case_answers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('medical_case_id')->unsigned();
//            $table->foreign('medical_case_id')->references('id')->on('medical_cases');
            $table->integer('answer_id')->unsigned();
//            $table->foreign('answer_id')->references('id')->on('answers');
            $table->string('value');
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
        Schema::dropIfExists('medical_case_answers');
    }
}
