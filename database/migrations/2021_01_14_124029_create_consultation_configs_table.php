<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsultationConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('consultation_configs', function (Blueprint $table) {
        //     $table->bigIncrements('id');
        //     $table->text('config')->nullable();
        //     $table->integer('version_id')->unsigned();
        //     $table->foreign('version_id')->references('id')->on('versions');
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('consultation_configs');
    }
}
