<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnsInFormulations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('formulations', function (Blueprint $table) {
          if(! Schema::hasColumn('formulations', 'administration_route_category')){
            $table->string('administration_route_category')->nullable();
          }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('formulations', function (Blueprint $table) {
            //
        });
    }
}
