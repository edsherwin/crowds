<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ValidateUserContactInfo;
use App\Http\Requests\ValidateChangePassword;
use Illuminate\Support\Facades\Hash;
use Auth;

class AccountSettingsController extends Controller
{
    public function edit() {
    	$detail = Auth::user()->detail;
    	$settings = Auth::user()->setting;

    	return view('account', compact('detail', 'settings'));
    }


    public function updatePassword(ValidateChangePassword $request) {

    	Auth::user()->update([
    		'password' => Hash::make(request('password'))
    	]);

    	return back()
    		->with('alert', ['type' => 'success', 'text' => 'Updated password']);
    }


    public function updateContact(ValidateUserContactInfo $request) {
    	Auth::user()->detail->update([
    		'phone_number' => request('phone_number'),
    		'messenger_id' => request('messenger_id')
    	]);

    	return back()
    		->with('alert', ['type' => 'success', 'text' => 'Updated contact details']);
    }


    public function updateNotifications() {

    	Auth::user()->setting->update([
			'is_orders_notification_enabled' => request()->has('new_order'),
			'is_bid_notification_enabled' => request()->has('new_bid'),
			'is_bid_accepted_notification_enabled' => request()->has('bid_accepted'),
			'is_bid_cancelled_notification_enabled' => request()->has('bid_cancelled'),
    	]);

    	return back()
    		->with('alert', ['type' => 'success', 'text' => 'Updated notification settings']);
    }
}
