<?php

use Illuminate\Database\Seeder;
use App\Patient;
class poppulate_data extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // loop
        // p = patirnt create 
        // p.medical cases 
        Patient::create([
            'first_name'=>'Ibu',
            'last_name'=>'black'
        ]);
    }
}
