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
        		'status' => ($model->status == 'no_show') ? 'posted' : $model->status
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

    public function fulfill() {
        $this->status = 'fulfilled';
        return $this;
    }

    public function noShow() {
        $this->status = 'no_show';
        return $this;
    }

    public function scopeByUser($query) {
        return $query->where('user_id', Auth::id());
    }
}
