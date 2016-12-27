<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(\App\Models\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
        'is_root' => false,
        'is_blocked' => $faker->boolean(10)
    ];
});

$factory->defineAs(\App\Models\User::class, 'admin', function (Faker\Generator $faker) use ($factory) {
    $user = $factory->raw(\App\Models\User::class);

    return array_merge($user, ['is_root' => true]);
});

$factory->define(\App\Models\Project::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
    ];
});