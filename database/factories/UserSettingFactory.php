<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\UserDetail;
use Faker\Generator as Faker;
use App\UserSetting;

$factory->define(UserSetting::class, function (Faker $faker) {
    return [
        'is_orders_notification_enabled' => false,
        'is_bid_notification_enabled' => false,
        'is_bid_accepted_notification_enabled' => false
    ];
});
