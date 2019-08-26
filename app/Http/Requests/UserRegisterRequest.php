<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRegisterRequest extends FormRequest
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
            'firstName' => 'required',
            'lastName' => 'required',
            'fullName' => 'required',
            'address' => 'required',
            'gender' => 'required',
            'nationality' => 'required',
            'birthDate' => 'required',
            'email' => 'required|email',
            'phoneNumber' => 'required',
            'mobilePhone' => 'required',
            'password' => 'required|confirmed|min:6',
            'image_id' => 'required'
        ];
    }
}
