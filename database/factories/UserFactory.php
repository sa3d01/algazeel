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
        'mobile' => '05'.rand(11111111,99999999),
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '12345678',
        'remember_token' => Str::random(10),
        'status' => 1,
        'user_type_id' => $faker->randomElement([3, 4]),
        'image' => $faker->randomElement(['0WhjhQRcSG.jpeg', '1bUjhnpa5v.jpg','3JcGxWK1Pe.jpeg','9WS22UZ01K.jpg','15AEL6tLCp.jpg']),
        'device->type'=>$faker->randomElement(['IOS','ANDROID']),
        'device->id'=>$faker->randomElement(['IOS','ANDROID']),
    ];
});
