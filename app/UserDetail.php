<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class UserDetail extends Model
{
    public $timestamps = false;

    protected $fillable = ['user_id', 'address', 'phone_number'];

    public function user() {
    	return $this->belongsTo(User::class);
    }
}
