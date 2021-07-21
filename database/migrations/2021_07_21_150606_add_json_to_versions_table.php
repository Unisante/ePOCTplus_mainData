<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJsonToVersionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('versions', function (Blueprint $table) {
            $table->json('medal_r_config')->nullable();
            $table->json('medal_r_json')->nullable();
            $table->integer('medal_r_json_version')->nullable();
            $table->boolean('archived')->nullable();
            $table->boolean('is_control_arm')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('versions', function (Blueprint $table) {
            $table->dropColumn('medal_r_config');
            $table->dropColumn('medal_r_json');
            $table->dropColumn('medal_r_json_version');
            $table->dropColumn('archived');
            $table->dropColumn('is_control_arm');
        });
    }
}
