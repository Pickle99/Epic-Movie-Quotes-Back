<?php

namespace App\Http\Controllers;

use App\Http\Requests\Movie\StoreMovieRequest;
use App\Http\Requests\Movie\UpdateMovieRequest;
use App\Http\Resources\MovieResource;
use App\Models\Genre;
use App\Models\GenreMovie;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;

class MovieController extends Controller
{
	public function store(StoreMovieRequest $request)
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

		return new MovieResource($movie);
	}

	public function showUserMovies()
	{
		$userId = auth()->user()->getAuthIdentifier();
		$userMovies = Movie::where('user_id', $userId)->with('quotes')->get();
		return MovieResource::collection($userMovies);
	}

	public function showMovieDescription(Movie $movie)
	{
		$movie = Movie::where('id', $movie->id)->with('quotes')->first();
		return new MovieResource($movie);
	}

	public function showMovie(Movie $movie)
	{
		return new MovieResource($movie);
	}

	public function showMovieWithGenres(Movie $movie)
	{
		return new MovieResource($movie);
	}

	public function update(UpdateMovieRequest $request, Movie $movie): JsonResponse
	{
		if (auth()->user()->id !== $movie->user_id)
		{
			return response()->json(['forbidden' => 'You dont have permission to edit other people movie'], 403);
		}

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
		return response()->json(['message' => 'Movie updated successfully!']);
	}

	public function destroy(Movie $movie): JsonResponse
	{
		if (auth()->user()->id !== $movie->user_id)
		{
			return response()->json(['forbidden' => 'You dont have permission to delete other people movie'], 403);
		}
		$movie->delete();
		return response()->json(['message' => 'Movie successfully deleted']);
	}

	public function showAllMovies()
	{
		$movie = Movie::all();
		return MovieResource::collection($movie);
	}
}
