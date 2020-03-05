<?php
 
/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Patient;
use Illuminate\Support\Str;
use Faker\Generator as Faker;
 
/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/
 
$factory->define(Patient::class, function (Faker $faker) {
    return [
        'first_name' => $faker->sentence($nbWords = 6, $variableNbWords = true),  // Random task title
        'last_name' => $faker->text(), // Random task description
    ];
});