<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
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
            'phone_number' => 'required_without:email',
            'email' => 'required_without:phone_number|nullable|email',
        ];
    }

    public function messages()
    {
        return [
            'phone_number.required_without' => 'Please provide phone number',
            'email.required_without' => 'Please provide email',
            'email.email' => 'Please provide valid email'
        ];
    }
}
