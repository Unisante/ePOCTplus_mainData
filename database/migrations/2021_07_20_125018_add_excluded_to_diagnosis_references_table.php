<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExcludedToDiagnosisReferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('diagnosis_references', function (Blueprint $table) {
            $table->boolean('excluded')->nullable()->default(false);
            $table->boolean('agreed')->nullable()->change();
            $table->boolean('proposed_additional')->nullable()->change();
            $table->renameColumn('proposed_additional', 'additional');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('diagnosis_references', function (Blueprint $table) {
            $table->removeColumn('excluded');
            $table->boolean('agreed')->nullable(false)->change();
            $table->renameColumn('additional', 'proposed_additional');
            $table->boolean('proposed_additional')->nullable(false)->change();
        });
    }
}
