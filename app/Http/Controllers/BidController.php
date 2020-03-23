<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ValidateBid;
use App\Bid;
use App\Order;
use Auth;
use App\Notifications\BidReceived;
use App\Notifications\BidAccepted;
use App\Notifications\BidCancelled;
use App\Notifications\BidNoShow;
use App\Notifications\BidFulfilled;

class BidController extends Controller
{
    public function create(ValidateBid $request) {
        try {
            $bid = Bid::submit(array_merge($request->validated(), [
                'user_id' => Auth::id(),
                'status' => 'posted'
            ]));

            $order_id = request('order_id');
            Order::find($order_id)->user->notify(new BidReceived($order_id, Auth::user()->name));

        } catch (\Exception $e) {
            return back()
                ->with('alert', ['type' => 'danger', 'text' => $e->getMessage()]);
        }
    	
        return back()
            ->with('alert', ['type' => 'success', 'text' => "Bid created! We'll notify you once this gets accepted."]);
    	
    }

    public function accept(Bid $bid) {

    	$bidder = request('_bidder');
    	$bid->accept()->save();

        $order_id = request('_order_id');
        $bid->user->notify(new BidAccepted($order_id, Auth::user()->name));

    	return back()
    		->with('alert', ['type' => 'success', 'text' => "Bid accepted! We notified {$bidder} so they can proceed."]);
    }

    public function fulfill(Bid $bid) {
        $bid->fulfill()->save();
        $order_id = request('_order_id');

        $bid->user->notify(new BidFulfilled($order_id, Auth::user()->name));

        return back()
            ->with('alert', ['type' => 'success', 'text' => "Order marked as <strong>fulfilled</strong>"]);
    }

    public function noShow(Bid $bid) {
        $bid->noShow()->save();
        $order_id = request('_order_id');

        $bid->user->notify(new BidNoShow($order_id, Auth::user()->name));

        return back()
            ->with('alert', ['type' => 'success', 'text' => "Order marked as <strong>no show</strong>"]);

    }

    public function index() {

        $bids = Bid::byUser()->with(['order', 'order.user'])->latest()->paginate(10);

        session()->flash('alert', ['type' => 'info', 'text' => "Once your bid is accepted, click on the contact button and contact the person first before proceeding with the service."]);

        return view('bids', compact('bids'));
    }

    public function cancel(Bid $bid) {

        $bid->cancel(request('cancel_reason'))->save();
        $order_id = $bid->order->id;
            Order::find($order_id)->user->notify(new BidCancelled($order_id, Auth::user()->name));

        return back()
            ->with('alert', ['type' => 'success', 'text' => "Your bid has been cancelled."]);
    }
}
