<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Bid;

class Order extends Model
{
    protected $fillable = [
    	'user_id', 'description', 'status'
    ];


    public function user() {
    	return $this->belongsTo(User::class);
    }

    public function bids() {
    	return $this->hasMany(Bid::class);
    }

    public function accept() {
    	return $this->status = 'accepted';
    }

}
