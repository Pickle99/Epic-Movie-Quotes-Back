<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMovieRequest extends FormRequest
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
			'title_en'           => 'required',
			'title_ka'           => 'required',
			'genres'             => ['required', Rule::in(['Horror', 'Comedy', 'Thriller', 'Action', 'Drama', 'Romantic'])],
			'director_en'        => 'required',
			'director_ka'        => 'required',
			'description_en'     => 'required',
			'description_ka'     => 'required',
			'year'               => 'integer',
			'budget'             => 'required|integer',
			'image'              => 'required|image',
		];
	}
}
