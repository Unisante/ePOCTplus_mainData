<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeviceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('device_types')->insert([
            'type' => "reader",
            'label' => "medAL-reader",
        ]);
        DB::table('device_types')->insert([
            'type' => "hub",
            'label' => "medAL-hub",
        ]);
    }
}
