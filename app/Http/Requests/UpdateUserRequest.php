<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
        $id = $this->route('user');
        $rules = User::$rules;
        if (!empty(auth()->user()) && auth()->user()->hasRole('admin')) {
            unset($rules['tc_checkbox']);
        }
        $rules['email'] = 'required|email|unique:users,email,' . $id;
        $rules['password'] = 'confirmed';
        return $rules;
    }
}
