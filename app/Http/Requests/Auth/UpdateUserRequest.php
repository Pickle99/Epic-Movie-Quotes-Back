<?php

namespace App\Http\Requests\Auth;

use App\Rules\LowerCase;
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
	 * @return array<string, mixed>
	 */
	public function rules()
	{
		return [
			'username' => ['min:3', 'max:15', 'alpha_num', 'regex:/^[A-Za-z0-9]+$/', new LowerCase()],
			'email'    => 'email',
			'password' => ['min:8', 'max:15', 'alpha_num', 'regex:/^[A-Za-z0-9]+$/', new LowerCase()],
			'avatar'   => 'image',
		];
	}

	public function messages()
	{
		return [
			'username.regex' => 'Username field must be alpha-numeric',
			'password.regex' => 'Password field must be alpha-numeric',
		];
	}
}
