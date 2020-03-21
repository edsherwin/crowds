<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use App\UserDetail;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use DB;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            // TODO: validate if province -> city -> barangay relationship is ok (e.g can't have a barangay from a different city or province)
            'province' => ['required', 'string', 'exists:provinces,id'],
            'city' => ['required', 'string', 'exists:cities,id'],
            'barangay' => ['required', 'string', 'exists:barangays,id'],

            'address' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'numeric', 'digits:11'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function showRegistrationForm() {
        // NOTE: hard-coded for now because it will only be used in a single city
        // LATER TODO: the cities and barangays should be loaded via ajax
        $provinces = DB::table('provinces')->where('id', 1)->get();
        $cities = DB::table('cities')->where('province_id', 1)->get();
        $barangays = DB::table('barangays')->where('city_id', 1)->get();

        return view('auth.register', compact('provinces', 'cities', 'barangays'));
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'barangay_id' => $data['barangay'],
            'password' => Hash::make($data['password']),
        ]);

        UserDetail::create([
            'user_id' => $user->id,
            'address' => $data['address'],
            'phone_number' => $data['phone_number']
        ]);

        return $user;
    }
}
