<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Order;
use Faker\Generator as Faker;
use App\UserDetail;

$factory->define(Order::class, function (Faker $faker) {
    return [
    	'user_id' => factory(UserDetail::class)->create()->user_id,
        'description' => $faker->text,
        'status' => 'posted'
    ];
});
