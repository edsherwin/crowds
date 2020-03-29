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

        // note: borderline hacky. 
        // might be more secure if Facebook PHP SDK is used without
        // the help of JavaScript SDK
        // also, create a service container to wrap this instead of calling the library directly
        $fb = new \Facebook\Facebook([
          'app_id' => config('services.facebook.app-id'),           
          'app_secret' => config('services.facebook.app-secret'),   
          'graph_api_version' => 'v5.0',
        ]);

        $access_token = request('_fb_access_token');

        try {
            $profile_response = $fb->get('/me', $access_token);
            $profile_body = $profile_response->getDecodedBody();

            $picture_response = $fb->get('/me/picture?redirect=false&type=large', $access_token);
            $picture_body = $picture_response->getDecodedBody();
      
            $profile_data = array_merge($profile_body, $picture_body);

            $facebook_user_exists = User::where('facebook_id', $profile_data['id'])->count();

            if ($facebook_user_exists) {
                
                return back()->with('alert', ['type' => 'danger', 'text' => "Someone has already connected that Facebook account previously."]);
            }

            Auth::user()->update([
                'facebook_id' => $profile_data['id'],
                'photo' => $profile_data['data']['url'],
                'setup_step' => 2
            ]);

        } catch(\Exception $e) {
            // the only way this will go wrong is if the user is messing around with hidden inputs
            return back()->with('alert', ['type' => 'danger', 'text' => "Sorry. We cannot find the Facebook account you're trying to connect."]);
        }

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
