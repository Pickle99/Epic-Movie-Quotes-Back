<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMovieRequest;
use App\Models\Genre;
use App\Models\GenreMovie;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MovieController extends Controller
{
	public function store(StoreMovieRequest $request): JsonResponse
	{
		$movie = new Movie;
		$movie->title = ['en' => $request->title_en, 'ka' => $request->title_ka];
		$movie->director = ['en' => $request->director_en, 'ka' => $request->director_ka];
		$movie->description = ['en' => $request->director_en, 'ka' => $request->director_ka];
		$movie->year = $request->year;
		$movie->budget = $request->budget;
		$movie->slug = Str::slug($request->title_en);
		$movie->user_id = auth()->user()->getAuthIdentifier();
		if ($request->hasFile('image'))
		{
			File::delete(public_path('images/') . $movie->image);
			$file = $request->file('image');
			$filename = $file->getClientOriginalName();
			$file->move('images/', $filename);
			$movie->image = $filename;
		}
		$movie->save();

		$genres = $request->genres;
		foreach ($genres as $genre)
		{
			$currentGenreId = Genre::where('name', $genre)->first()->id;
			$genreMovie = new GenreMovie();
			$genreMovie->movie_id = $movie->id;
			$genreMovie->genre_id = $currentGenreId;
			$genreMovie->save();
		}

		return response()->json('Movie created successfully');
	}

	public function getAllMovies(): JsonResponse
	{
		$movies = Movie::all();
		foreach ($movies as $movie)
		{
			$userOfMovie = $movie->user;
		}
		return response()->json(['movies' => $movies]);
	}

	public function getUserMovies(): JsonResponse
	{
		$userId = auth()->user()->getAuthIdentifier();
		$userMovies = Movie::where('user_id', $userId)->get();
		return response()->json(['movies' => $userMovies]);
	}

	public function getMovieDescription(int $id): JsonResponse
	{
		$movie = Movie::find($id);
		foreach ($movie->genres as $genre)
		{
			$genreMovie = $genre->name;
		}
		return response()->json([$movie]);
	}
}
