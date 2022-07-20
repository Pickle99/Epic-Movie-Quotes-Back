<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Http\Resources\MovieResource;
use App\Models\Genre;
use App\Models\GenreMovie;
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
		$movie->description = ['en' => $request->description_en, 'ka' => $request->description_ka];
		$movie->year = $request->year;
		$movie->budget = $request->budget;
		$movie->user_id = auth()->user()->getAuthIdentifier();
		if ($request->hasFile('image'))
		{
			File::delete(public_path('images/') . $movie->image);
			$file = $request->file('image');
			$filename = $file->getClientOriginalName();
			$file->move('images/', $filename);
			$movie->image = 'images/' . $filename;
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

	public function showUserMovies(): JsonResponse
	{
		$userId = auth()->user()->getAuthIdentifier();
		$userMovies = Movie::where('user_id', $userId)->get();
		return response()->json(['movies' => $userMovies]);
	}

	public function showMovieDescription(int $id): JsonResponse
	{
		$movie = Movie::with(['genres', 'quotes'])->where('id', $id)->get();
		return response()->json($movie);
	}

	public function showMovie(Movie $movie): JsonResponse
	{
		return response()->json([$movie]);
	}

	public function showMovieWithGenres(Movie $movie): JsonResponse
	{
		$movieWithGenres = Movie::with('genres')->where('id', $movie->id)->get();
		return response()->json($movieWithGenres);
	}

	public function update(UpdateMovieRequest $request, Movie $movie): JsonResponse
	{
		$movie->title = ['en' => $request->title_en, 'ka' => $request->title_ka];
		$movie->director = ['en' => $request->director_en, 'ka' => $request->director_ka];
		$movie->description = ['en' => $request->description_en, 'ka' => $request->description_ka];
		$movie->year = $request->year;
		$movie->budget = $request->budget;
		if ($request->hasFile('image'))
		{
			File::delete(public_path('images') . $movie->image);
			$file = $request->file('image');
			$filename = $file->getClientOriginalName();
			$file->move('images/', $filename);
			$movie->image = 'images/' . $filename;
		}

		$movie->update();
		GenreMovie::where('movie_id', $movie->id)->delete();
		$genres = $request->genres;
		foreach ($genres as $genre)
		{
			$currentGenreId = Genre::where('name', $genre)->first()->id;
			$genreMovie = new GenreMovie();
			$genreMovie->movie_id = $movie->id;
			$genreMovie->genre_id = $currentGenreId;
			$genreMovie->save();
		}
		return response()->json(['message' => 'Movie updated successfully!', 200]);
	}

	public function destroy(Movie $movie): JsonResponse
	{
		$movie->delete();
		return response()->json(['message' => 'Movie successfully deleted', 200]);
	}

	public function showAllMovies(): JsonResponse
	{
		$movie = Movie::all();
		return response()->json(MovieResource::collection($movie));
	}
}
