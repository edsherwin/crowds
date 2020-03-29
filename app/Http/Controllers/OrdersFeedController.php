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
            ->hasLessThanFivePostedBids()
    		->latest()
    		->paginate(10);

		$provinces = DB::table('provinces')->get();
		
    	return view('orders_feed', compact('orders', 'provinces'));
    }
}
