<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMovieRequest;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;

class MovieController extends Controller
{
	public function store(StoreMovieRequest $request): JsonResponse
	{
		$movie = new Movie;
		$movie->title = ['en' => $request->title_en, 'ka' => $request->title_ka];
		$movie->director = ['en' => $request->director_en, 'ka' => $request->director_ka];
		$movie->description = ['en' => $request->director_en, 'ka' => $request->director_ka];
		if ($request->hasFile('image'))
		{
			File::delete(public_path('images/') . $movie->image);
			$file = $request->file('image');
			$filename = $file->getClientOriginalName();
			$file->move('images/', $filename);
			$movie->image = $filename;
		}
		$movie->save();

		return response()->json('Movie created successfully');
	}
}
