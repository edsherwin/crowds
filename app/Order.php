<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Bid;
use Auth;

class Order extends Model
{
    protected $fillable = [
    	'user_id', 'barangay_id', 'description', 'status'
    ];


    public function user() {
    	return $this->belongsTo(User::class);
    }

    public function bids() {
    	return $this->hasMany(Bid::class);
    }

    public function postedBids() {
        return $this->hasMany(Bid::class)->where('status', 'posted');
    }

    public function accept() {
    	return $this->status = 'accepted';
    }

    public function scopePosted($query) {
        return $query->where('status', 'posted');
    }

    public function scopeSameBarangay($query) {
        return $query->where('barangay_id', Auth::user()->barangay_id);
    }

}
