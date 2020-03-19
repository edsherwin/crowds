<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    public $timestamps = false;

    protected $fillable = ['user_id', 'address', 'phone_number'];
}
