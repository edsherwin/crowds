<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\UserDetail;
use App\Order;
use App\Barangay;
use App\Bid;
use App\UserSetting;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'facebook_id', 'bot_user_id', 'photo', 'setup_step', 'unique_id', 'barangay_id', 'is_enabled'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function detail() {
        return $this->hasOne(UserDetail::class);
    }


    public function orders() {
        return $this->hasMany(Order::class);
    }


    public function hasNoBids($bids) {
        $user_ids = $bids->pluck('user_id')->toArray();
        return !in_array($this->id, $user_ids);
    }


    public function hasBids($bids) {
        $user_ids = $bids->pluck('user_id')->toArray();
        return in_array($this->id, $user_ids);
    }


    public function barangay() {
        return $this->belongsTo(Barangay::class);
    }

    public function ordersToday() {
        return $this->hasMany(Order::class)->whereRaw("DATE(created_at) = ?", [now()->toDateString()]);
    }

    public function bidsToday() {
        return $this->hasMany(Bid::class)
            ->whereRaw("DATE(created_at) = ?", [now()->toDateString()]);
    }

    public function bids() {
        return $this->hasMany(Bid::class);
    }

    public function setting() {
        return $this->hasOne(UserSetting::class);
    }
}
