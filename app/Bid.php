<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Order;
use Auth;

class Bid extends Model
{
    public $timestamps = false;

    protected $fillable = ['order_id', 'user_id', 'service_fee', 'notes', 'status'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });

        static::updating(function ($model) {

            $order_status = [
                'no_show' => 'posted',
                'accepted' => 'accepted',
                'cancelled' => null,
                'fulfilled' => 'fulfilled'
            ];

            if (!is_null($order_status[$model->status])) {
                $model->order->update([
                    'status' => $order_status[$model->status]
                ]);
            }

            if ($model->status == 'cancelled' && $model->order->postedBids->count() == 0) {
                $model->order->update([
                    'status' => 'posted'
                ]);
            }
        });
    }


    public function user()
    {
    	return $this->belongsTo(User::class);
    }


    public function order() {
    	return $this->belongsTo(Order::class);
    }


    public function accept() {
        // note: kinda fishy. might be better off putting the condition somewhere else?
        // also, the API doesn't look quite right. might be better putting these in the Order model itself?
        // same with fulfill() and noShow()
    	if ($this->order->user->id == Auth::id()) {
    		$this->status = 'accepted';
    	}
    	return $this;
    }

    public function fulfill() {
        if ($this->order->user->id == Auth::id()) {
            $this->status = 'fulfilled';
            return $this;
        }
        return $this;
    }

    public function noShow() {
        if ($this->order->user->id == Auth::id()) {
            $this->status = 'no_show';
        }
        return $this;
    }

    public function scopeByUser($query) {
        return $query->where('user_id', Auth::id());
    }

    public function cancel() {
        if ($this->user_id == Auth::id()) {
            $this->status = 'cancelled';
        }
        return $this;
    }

    public static function submit($data) {
        $order = Order::find($data['order_id']);

        if ($order->user->barangay_id != Auth::user()->barangay_id) {
            throw new \Exception("You can only submit a bid to orders posted in the same barangay as you.");
        }

        if (Auth::user()->hasBids($order->bids)) {
            throw new \Exception("You can no longer submit a bid to an order which you've previously cancelled.");
        }

        if ($order->status != 'posted' || $order->postedBids->count() >= 5) { 
            throw new \Exception("Bids can no longer be submitted to this order.");
        }

        self::create($data);
    }
}
