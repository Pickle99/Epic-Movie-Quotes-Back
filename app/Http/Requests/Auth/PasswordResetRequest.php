<?php

namespace App\Http\Requests\Auth;

use App\Rules\LowerCase;
use Illuminate\Foundation\Http\FormRequest;

class PasswordResetRequest extends FormRequest
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
			'email'    => 'required|email',
			'token'    => 'required',
			'password' => ['required', 'min:8', 'max:15', 'confirmed', 'alpha_num', 'regex:/^[A-Za-z0-9]+$/', new LowerCase()],
		];
	}

	public function messages()
	{
		return [
			'password.regex' => 'Password field must be alpha-numeric',
		];
	}
}
