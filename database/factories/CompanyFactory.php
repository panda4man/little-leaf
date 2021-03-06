<?php

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

$factory->define(App\Models\Company::class, function (Faker $faker) {
    return [
        'user_id' => null,
        'name'    => ucfirst($faker->word),
        'address' => $faker->streetAddress,
        'city'    => $faker->city,
        'state'   => $faker->stateAbbr,
        'zip'     => $faker->postcode,
        'country' => $faker->country,
    ];
});
