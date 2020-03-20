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
}