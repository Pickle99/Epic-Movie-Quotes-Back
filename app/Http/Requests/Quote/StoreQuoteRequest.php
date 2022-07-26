<?php

namespace App\Http\Requests\Quote;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuoteRequest extends FormRequest
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
	 * @return array<string, mixed>
	 */
	public function rules()
	{
		return [
			'text_en' => 'required|regex:/^[A-Za-z]+$/',
			'text_ka' => 'required|regex:/^[ა-ჰ]+$/',
			'image'   => 'required|image',
		];
	}
}
