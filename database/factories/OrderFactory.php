<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Order;
use Faker\Generator as Faker;
use App\User;
use App\UserDetail;
use App\UserSetting;

$factory->define(Order::class, function (Faker $faker) {
	$user = factory(User::class)->create();
	factory(UserDetail::class)->create([
		'user_id' => $user->id
	]);

	factory(UserSetting::class)->create([
		'user_id' => $user->id
	]);

    return [
    	'user_id' => $user->id,
    	'barangay_id' => $user->barangay_id,
        'description' => $faker->text,
        'status' => 'posted'
    ];
});
