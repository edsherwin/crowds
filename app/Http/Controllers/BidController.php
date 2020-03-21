<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ValidateBid;
use App\Bid;
use Auth;

class BidController extends Controller
{
    public function create(ValidateBid $request) {

        try {
            Bid::submit(array_merge($request->validated(), [
                'user_id' => Auth::id(),
                'status' => 'posted'
            ]));
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

    	return back()
    		->with('alert', ['type' => 'success', 'text' => "Bid accepted! We notified {$bidder} so they can proceed."]);
    }

    public function fulfill(Bid $bid) {
        $bid->fulfill()->save();
        return back()
            ->with('alert', ['type' => 'success', 'text' => "Order marked as <strong>fulfilled</strong>"]);
    }

    public function noShow(Bid $bid) {
        $bid->noShow()->save();
        return back()
            ->with('alert', ['type' => 'success', 'text' => "Order marked as <strong>no show</strong>"]);

    }

    public function index() {

        $bids = Bid::byUser()->with(['order', 'order.user'])->latest()->paginate(10);
        return view('bids', compact('bids'));
    }

    public function cancel(Bid $bid) {

        $bid->cancel(request('cancel_reason'))->save();
        return back()
            ->with('alert', ['type' => 'success', 'text' => "Your bid has been cancelled."]);
    }
}
