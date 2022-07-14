<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\JsonResponse;

class GenreController extends Controller
{
	public function showGenres(): JsonResponse
	{
		$genres = Genre::all();
		return response()->json($genres);
	}
}
