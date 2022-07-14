<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuoteRequest;
use App\Http\Requests\UpdateQuoteRequest;
use App\Models\Movie;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;

class QuoteController extends Controller
{
	public function store(StoreQuoteRequest $request, Movie $movie): JsonResponse
	{
		$quote = new Quote;
		$quote->text = ['en' => $request->text_en, 'ka' => $request->text_ka];
		$quote->movie_id = $movie->id;
		if ($request->hasFile('image'))
		{
			File::delete(public_path('images/') . $quote->image);
			$file = $request->file('image');
			$filename = $file->getClientOriginalName();
			$file->move('images/', $filename);
			$quote->image = ('images/' . $filename);
		}
		$quote->save();

		return response()->json('Movie created successfully');
	}

	public function create(Quote $quote): JsonResponse
	{
		return response()->json([$quote]);
	}

	public function update(UpdateQuoteRequest $request, Quote $quote): JsonResponse
	{
		$quote->text = ['en' => $request->text_en, 'ka' => $request->text_ka];
		if ($request->hasFile('image'))
		{
			File::delete(public_path('images') . $quote->image);
			$file = $request->file('image');
			$filename = $file->getClientOriginalName();
			$file->move('images/', $filename);
			$quote->image = 'images/' . $filename;
		}
		$quote->update();
		return response()->json('Quote updated successfully!');
	}
}
