<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ParentRegRequest extends UserRegisterRequest
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
            'relation' => 'required|in:father,mother',
            'edu_degree' => 'required',
            'profession' => 'required',
            'job' => 'required',
            'company_name' => 'required',
            'work_phone' => 'required',
            'position' => 'required',
            'student_id' => 'required'
        ];
    }
}
