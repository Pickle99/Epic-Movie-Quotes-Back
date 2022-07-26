<?php

namespace App\Http\Requests\Movie;

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
			'title_en'             => 'required|regex:/^[A-Za-z]+$/',
			'title_ka'             => 'required|regex:/^[ა-ჰ]+$/',
			'genres.*'             => ['required', Rule::in(['Horror', 'Comedy', 'Thriller', 'Action', 'Drama', 'Romantic'])],
			'director_en'          => 'required|regex:/^[A-Za-z]+$/',
			'director_ka'          => 'required|regex:/^[ა-ჰ]+$/',
			'description_en'       => 'required|regex:/^[A-Za-z]+$/',
			'description_ka'       => 'required|regex:/^[ა-ჰ]+$/',
			'year'                 => 'integer',
			'budget'               => 'required|integer',
			'image'                => 'required|image',
		];
	}
}
