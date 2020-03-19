<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;

class OrdersFeedController extends Controller
{
    public function index() {
    	$orders = Order::latest()->paginate(10);
    	return view('orders_feed', compact('orders'));
    }
}
