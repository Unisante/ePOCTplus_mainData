<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nodes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('medal_c_id')->unsigned();
            $table->string('reference');
            $table->string('label');
            $table->string('type');
            $table->string('category');
            $table->string('priority');
            $table->string('stage');
            $table->string('description');
            $table->string('formula');
            $table->integer('answer_type_id')->unsigned();
            $table->foreign('answer_type_id')->references('id')->on('answer_types');
            $table->integer('algorithm_id')->unsigned();
            $table->foreign('algorithm_id')->references('id')->on('algorithms');
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
        Schema::dropIfExists('nodes');
    }
}
