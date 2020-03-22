<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use DB;

class OrdersFeedController extends Controller
{
    public function index() {
    	$orders = Order::with(['user', 'user.detail', 'postedBids'])
    		->posted()
    		->sameBarangay()
    		->createdWithinADay()
    		->latest()
    		->paginate(10);
    	
		$provinces = DB::table('provinces')->where('id', 1)->get();
		$cities = DB::table('cities')->where('province_id', 1)->get();
		$barangays = DB::table('barangays')->where('city_id', 1)->get();

    	return view('orders_feed', compact('orders', 'provinces', 'cities', 'barangays'));
    }
}
