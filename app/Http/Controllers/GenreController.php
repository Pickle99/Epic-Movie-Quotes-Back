<?php

namespace App\Http\Controllers;

use App\Http\Resources\GenreResource;
use App\Models\Genre;

class GenreController extends Controller
{
	public function showGenres()
	{
		$genres = Genre::all();
		return GenreResource::collection($genres);
	}
}
