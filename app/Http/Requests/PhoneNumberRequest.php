<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PhoneNumberRequest extends FormRequest
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
			'area_code' => 'required|numeric',
			'number' => 'required|numeric'
        ];
    }

    public function messages(){
    	return [
    		'area_code.numeric' => 'The area code must only contain numbers. No dashes, spaces or any punctuation.',
			'number.numeric' => 'The phone number must only contain numbers. No dashes, spaces or any punctuation.'
		];
	}
}
