<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ValidateUserAddress;
use App\Http\Requests\ValidateFacebookAccount;
use App\Http\Requests\ValidateUserContactInfo;
use Auth;
use App\User;

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

    	Auth::user()->update([
    		'setup_step' => 3
    	]);

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

        return $user->detail;
    }
}
