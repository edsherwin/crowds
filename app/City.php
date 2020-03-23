<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Barangay;

class City extends Model
{
    public $timestamps = false;

    public function barangays() {
    	return $this->hasMany(Barangay::class);
    }
}
