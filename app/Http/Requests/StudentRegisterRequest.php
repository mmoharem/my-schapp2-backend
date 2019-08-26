<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentRegisterRequest extends UserRegisterRequest
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
//            'firstName' => 'required',
//            'lastName' => 'required',
//            'address' => 'required',
//            'gender' => 'required',
//            'birthDate' => 'required',
//            'email' => 'required|email',
//            'phoneNumber' => 'required',
//            'mobilePhone' => 'required',
//            'password' => 'required|confirmed|min:6',
//            'image_id' => 'required',
            'grade_id' => 'required',
            'gardian' => 'required',
        ];
    }
}
