<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateUserContactInfo extends FormRequest
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
            'phone_number' => 'required_without:messenger_id|nullable|numeric|digits:11',
            'messenger_id' => 'required_without:phone_number|nullable|regex:/^[A-Z0-9a-z._-]+$/'
        ];
    }
}
