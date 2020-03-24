<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class ValidateFacebookAccount extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            '_fb_profile_id' => 'required|numeric|digits_between:17,30|unique:users,facebook_id,' . Auth::id(),
            '_fb_profile_pic' => 'required|url'
        ];
    }
}
