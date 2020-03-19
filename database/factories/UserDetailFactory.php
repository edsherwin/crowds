<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\UserDetail;
use Faker\Generator as Faker;
use App\User;

$factory->define(UserDetail::class, function (Faker $faker) {
    return [
        'user_id' => factory(User::class)->create()->id,
        'address' => $faker->address,
        'phone_number' => $faker->phoneNumber
    ];
});
