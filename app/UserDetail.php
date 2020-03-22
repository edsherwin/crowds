<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class UserDetail extends Model
{
    public $timestamps = false;

    protected $fillable = ['user_id', 'address', 'phone_number', 'messenger_id'];

    public function user() {
    	return $this->belongsTo(User::class);
    }
}
