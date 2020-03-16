<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //  $this->call(AnswerTypeSeeder::class);
        //  $this->call(NodeSeeder::class);
        // $this->call(AnswerSeeder::class);
         $this->call(PatientSeeder::class);

    }
}
