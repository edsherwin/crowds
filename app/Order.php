<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Bid;
use Auth;

class Order extends Model
{
    protected $fillable = [
    	'user_id', 'barangay_id', 'is_barangay_only', 'description', 'status'
    ];


    public function user() {
    	return $this->belongsTo(User::class);
    }

    public function bids() {
    	return $this->hasMany(Bid::class);
    }

    public function bidsAcceptedFirst() {
        return $this->hasMany(Bid::class)->orderBy('status', 'ASC'); // accepted, cancelled, fulfilled, no show, posted
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

    public function scopeCreatedWithinADay($query) {
        $datetime_now = now()->toDatetimeString();
        return $query->whereRaw("TIMESTAMPDIFF(HOUR, created_at, '{$datetime_now}') <= 24");
    }

    public function scopeHasLessThanFivePostedBids($query) {
       return $query->has('postedBids', '<', 5);
    }

    public function scopeOlderThanHours($query, $hours) {
        return $query->where('created_at', '<=', now()->subHours($hours)->toDateTimeString());
    }

    public function scopeGeneral($query) {
        return $query->where('is_barangay_only', false);
    }

    public function scopeBarangay($query) {
        return $query->where('is_barangay_only', true);
    }

    public function fulfill() {
        if (Auth::user()->user_type == 'officer' && Auth::user()->barangay_id == $this->barangay_id) {
            $this->update([
                'status' => 'fulfilled'
            ]);
            return $this;
        }
        abort(403, "You're not allowed to update this data");
    }

    public function scopeUnfulfilled($query) {
        return $query->whereIn('status', ['posted', 'accepted']);
    }

}
