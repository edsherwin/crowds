<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ValidateBid;
use App\Bid;
use Auth;

class BidController extends Controller
{
    public function create(ValidateBid $request) {

    	Bid::create(array_merge($request->validated(), [
    		'user_id' => Auth::id(),
    		'status' => 'posted'
    	]));

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
}
