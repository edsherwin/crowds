<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\City;

class Province extends Model
{
    public $timestamps = false;

    public function cities() {
    	return $this->hasMany(City::class);
    }
}
