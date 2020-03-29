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
            '_fb_profile_id' => 'required|numeric|unique:users,facebook_id',
            '_fb_profile_pic' => 'required|url'
        ];
    }

    public function messages()
    {
        return [
            '_fb_profile_id.unique' => 'Facebook profile has already been connected previously by another user.',
            '_fb_profile_pic.required'  => 'Facebook profile picture is required.',
        ];
    }
}
