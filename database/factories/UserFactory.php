<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

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

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '123456',
        'remember_token' => Str::random(10),
        'status' => $faker->randomElement([1, 0, null]),
        'user_type_id' => $faker->randomElement([1, 2]),
        'image' => $faker->randomElement(['AVB9Ed0J9m.jpeg', '15AEL6tLCp.jpg','3JcGxWK1Pe.jpeg','KYbrGG5fva.jpg']),
    ];
});
