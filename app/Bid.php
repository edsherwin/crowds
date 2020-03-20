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
        	$model->order->update([
        		'status' => 'accepted'
        	]);
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
    	if ($this->order->user->id == Auth::id()) {
    		$this->status = 'accepted';
    	}
    	return $this;
    }
}
