<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
			'username' => 'required|max:40|alpha_dash',
			'password' => 'required|min:7|alpha_dash|confirmed'
        ];
    }

    public function messages(){
    	return [
    		'username.alpha_dash' => 'The username may only contain letters, numbers, dashes and underscores. No spaces.',
			'password.alpha_dash' => 'The password may only contain letters, numbers, dashes and underscores. No spaces.'
		];
	}
}
