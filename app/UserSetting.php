<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    public $timestamps = false;

    protected $fillable = ['user_id', 'is_orders_notification_enabled', 'is_bid_notification_enabled', 'is_bid_accepted_notification_enabled'];
}
