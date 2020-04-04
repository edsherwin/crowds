<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class OfficerAccountRequest extends Model
{
    protected $fillable = ['user_id'];

    public static function submit() {
    	if (!Auth::user()->officerAccountRequests) {
			return self::create([
				'user_id' => Auth::id()
			]);
    	}
    	abort(401, "You've already requested previously.");
    }
}
