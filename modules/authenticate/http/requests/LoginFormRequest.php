<?php namespace Modules\Authenticate\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginFormRequest extends FormRequest {

    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            'email'    => 'required|email',
            'password' => 'required',
            'remember' => 'required'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.required' => trans('authenticate::messages.Email is required'),
            'email.email' => trans('authenticate::messages.Invalid Email'),
            'password.required' => trans('authenticate::messages.Password is required'),
            'remember.required' => trans('authenticate::messages.Remember check is required'),
        ];
    }
}