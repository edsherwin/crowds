<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Order;
use Faker\Generator as Faker;
use App\UserDetail;

$factory->define(Order::class, function (Faker $faker) {
	$user_detail = factory(UserDetail::class)->create();
    return [
    	'user_id' => $user_detail->user_id,
    	'barangay_id' => $user_detail->user->barangay_id,
        'description' => $faker->text,
        'status' => 'posted'
    ];
});
