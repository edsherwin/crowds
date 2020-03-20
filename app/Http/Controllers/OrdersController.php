<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ValidateOrder;
use App\Order;
use Auth;

class OrdersController extends Controller
{
    public function create(ValidateOrder $request) {

    	Order::create(array_merge($request->validated(), [
    		'user_id' => Auth::id(),
    		'status' => 'posted'
    	]));

    	return back()
    		->with('alert', ['type' => 'success', 'text' => "Request Created! <br/>Please wait for someone in your neighborhood to submit a bid."]);
    }


    public function index() {
    	
    	$orders = Order::where('user_id', Auth::id())
    		->with(['bids', 'bids.user'])
    		->latest()
    		->paginate(10);

    	return view('orders', compact('orders'));
    }
}
