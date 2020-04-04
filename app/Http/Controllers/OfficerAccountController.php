<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\OfficerAccountRequest;
use Auth;

class OfficerAccountController extends Controller
{
    public function requestAccount() {
    	OfficerAccountRequest::submit();
    		
    	return back()->with('alert', ['type' => 'success', 'text' => 'Officer account requested! Please wait for it to get approved in a few days.']);
    }
}
