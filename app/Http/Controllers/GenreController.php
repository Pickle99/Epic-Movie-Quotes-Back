<?php

namespace App\Http\Controllers;

use App\Http\Resources\GenreResource;
use App\Models\Genre;
use Illuminate\Http\Resources\Json\ResourceCollection;

class GenreController extends Controller
{
	public function showGenres(): ResourceCollection
	{
		$genres = Genre::all();
		return GenreResource::collection($genres);
	}
}
