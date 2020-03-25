<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\UserDetail;
use Faker\Generator as Faker;

$factory->define(UserDetail::class, function (Faker $faker) {
    return [
        'phone_number' => $faker->phoneNumber,
        'messenger_id' => $faker->userName
    ];
});
