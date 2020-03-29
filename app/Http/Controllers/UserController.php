<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ValidateUserAddress;
use App\Http\Requests\ValidateFacebookAccount;
use App\Http\Requests\ValidateUserContactInfo;
use Auth;
use App\User;
use App\Order;

class UserController extends Controller
{
    public function completeSetupStepOne(ValidateUserAddress $request) {
    	
    	Auth::user()->update([
    		'barangay_id' => request('barangay'),
    		'setup_step' => 1
    	]);

    	return back();
    }

    public function completeSetupStepTwo(ValidateFacebookAccount $request) {

    	Auth::user()->update([
            'facebook_id' => request('_fb_profile_id'),
    		'photo' => request('_fb_profile_pic'),
    		'setup_step' => 2
    	]);

    	return back();
    }


    public function completeSetupStepThree(ValidateUserContactInfo $request) {

    	Auth::user()->detail->update([
    		'phone_number' => request('phone_number'), 
    		'messenger_id' => request('messenger_id')
    	]);

        $setup_step = request()->has('_is_ios') ? 4 : 3;

    	Auth::user()->update([
    		'setup_step' => $setup_step // note: if device is iOS, skip directly to step 4 because there's no web notifications in iOS devices
    	]);

        if ($setup_step == 4) {
            return back()->with('alert', ['type' => 'success', 'text' => "Setup complete! You can now make requests and submit bids."]);
        }

    	return back();
    }


    public function completeSetupStepFour() {
        Auth::user()->update([
            'fcm_token' => request('_fcm_token'),
            'setup_step' => 4,
        ]);

        return back()->with('alert', ['type' => 'success', 'text' => "Setup complete! You can now make requests and submit bids."]);
    }


    public function previousSetupStep() {
    	if (Auth::user()->setup_step >= 1) {
    		Auth::user()->decrement('setup_step');
    	}
    	return back();
    }


    public function show(User $user) {
        // note: kinda fishy. there might be a more elegant or more performant way to do this
        $accepted_order_ids = Auth::user()->bidsAcceptedToday->pluck('order_id')->toArray();
        $viewable_user_ids = Order::whereIn('id', $accepted_order_ids)->pluck('user_id')->toArray();

        if (in_array($user->id, $viewable_user_ids) || $user->id == Auth::id()) {
            return $user->detail;
        }
        abort(401, "You cannot access this user's info");
    }

}
