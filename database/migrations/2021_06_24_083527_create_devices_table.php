<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('name');
            $table->string('type');
            $table->string('mac_address')->nullable();
            $table->string('model')->nullable();
            $table->string('brand')->nullable();
            $table->string('os')->nullable();
            $table->string('os_version')->nullable();
            $table->string('redirect');
            $table->tinyInteger('status')->default('0');
            $table->bigInteger('user_id');
            $table->bigInteger('health_facility_id')->nullable();
            $table->bigInteger('oauth_client_id')->nullable();
            $table->string('oauth_client_secret')->nullable();
            $table->datetime('last_seen')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('health_facility_id')->references('id')->on('health_facilities')->onDelete('cascade');
            $table->foreign('oauth_client_id')->references('id')->on('oauth_clients');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('devices');
    }
}
