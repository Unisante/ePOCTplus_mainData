<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Version;

class PopulateIsArmControlVersions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach(Version::all() as $version){
            ini_set("allow_url_fopen", 1);
            $json = file_get_contents('https://medalc.unisante.ch/api/v1/versions/' . $version->medal_c_id);
            $obj = json_decode($json);
            $is_arm_control = $obj->medal_r_json->is_arm_control;
            $version->update([
                'is_arm_control' => $is_arm_control
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
