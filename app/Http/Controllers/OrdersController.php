<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ValidateOrder;
use App\Order;
use App\User;
use Auth;
use App\Notifications\OrderCreated;
use Illuminate\Support\Facades\Notification;

class OrdersController extends Controller
{
    public function create(ValidateOrder $request) {

        Order::create(array_merge($request->validated(), [
            'user_id' => Auth::id(),
            'barangay_id' => Auth::user()->barangay_id,
            'status' => 'posted'
        ]));

        // note: might be better making it as a listener? 
        $users = User::whereHas('setting', function($q) {
            $q->where('is_orders_notification_enabled', true);
        })
            ->where('id', '!=', Auth::id())
            ->where('barangay_id', Auth::user()->barangay_id)
            ->get();

        Notification::send($users, new OrderCreated);

        return back()
            ->with('alert', ['type' => 'success', 'text' => "Request Created! <br/>Please wait for someone in your neighborhood to submit a bid."]);
    }


    public function index() {
        
        $orders = Order::where('user_id', Auth::id())
            ->with(['bidsAcceptedFirst', 'bids.user'])
            ->general()
            ->latest()
            ->paginate(10);

        if (!session()->has('alert')) {
            session()->now('alert', ['type' => 'info', 'text' => "Once you've accepted a bid, click on the contact button and contact the person first to make sure they're legit. Click the no show button if you can't reach them."]);
        }

        return view('orders', compact('orders'));
    }
}
