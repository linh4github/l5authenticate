<?php

namespace Modules\Authenticate\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class RegisterFormRequest extends FormRequest
{
    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8'
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
            'email.email' => trans('authenticate::messages.Invalid email'),
            'email.unique' => trans('authenticate::messages.This email is exists'),
            'password.required' => trans('authenticate::messages.Password is required'),
            'password.min' => trans('authenticate::messages.Password is less then 8 characters'),
        ];
    }

    /*public function response(array $errors)
    {
        dd($errors);
    }*/
}