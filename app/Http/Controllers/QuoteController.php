<?php

namespace App\Http\Controllers;

use App\Http\Requests\Quote\StoreQuoteRequest;
use App\Http\Requests\Quote\StoreWriteQuoteRequest;
use App\Http\Requests\Quote\UpdateQuoteRequest;
use App\Http\Resources\QuoteResource;
use App\Models\Movie;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class QuoteController extends Controller
{
	public function store(StoreQuoteRequest $request, Movie $movie): JsonResponse
	{
		if (auth()->user()->id !== $movie->user_id)
		{
			return response()->json(['forbidden' => 'You dont have permission to add quotes here'], 403);
		}

		$quote = new Quote;
		$quote->text = ['en' => $request->text_en, 'ka' => $request->text_ka];
		$quote->movie_id = $movie->id;
		$quote->user_id = auth()->user()->getAuthIdentifier();
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

	public function showQuote(Quote $quote)
	{
		$quoteWithUser = Quote::where('id', $quote->id)->with('user')->first();
		return new QuoteResource($quoteWithUser);
	}

	public function update(UpdateQuoteRequest $request, Quote $quote): JsonResponse
	{
		if (auth()->user()->id !== $quote->user_id)
		{
			return response()->json(['forbidden' => 'You dont have permission to edit other people quotes'], 403);
		}
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
		return response()->json(['message'=>'Quote updated successfully', 200]);
	}

	public function showPaginatedQuotes()
	{
		$str = request('search');
		if (str_starts_with($str, 'q'))
		{
			$search = substr($str, 1);
			$quotes = Quote::with(['user', 'movie'])
				->where('text', 'like', '%' . $search . '%')->orderByDesc('created_at')
				->paginate(1);
			return QuoteResource::collection($quotes);
		}
		if (str_starts_with($str, 'm'))
		{
			$search = substr($str, 1);
			$quotes = Quote::with(['user', 'movie'])
				->whereHas('movie', function ($q) use ($search) {
					$q->where('title', 'like', '%' . $search . '%');
				})->orderByDesc('created_at')
				->paginate(1);
			return QuoteResource::collection($quotes);
		}
		$quotes = Quote::with(['user', 'movie'])->orderByDesc('created_at')->paginate(1);
		return QuoteResource::collection($quotes);
	}

	public function showAllQuotes()
	{
		$quotes = Quote::with(['user', 'movie'])->orderByDesc('created_at')->get();
		return QuoteResource::collection($quotes);
	}

	public function destroy(Quote $quote): JsonResponse
	{
		if (auth()->user()->id !== $quote->user_id)
		{
			return response()->json(['forbidden' => 'You dont have permission to delete other people quotes'], 403);
		}
		$quote->delete();
		return response()->json(['message' => 'Quote deleted']);
	}

	public function storeWriteQuote(StoreWriteQuoteRequest $request): JsonResponse
	{
		$quote = new Quote();
		$quote->text = ['en' => $request->text_en, 'ka' => $request->text_ka];
		$quote->image = $request->image;
		$quote->movie_id = $request->movieId;
		$quote->user_id = Auth::id();
		if ($request->hasFile('image'))
		{
			File::delete(public_path('images/') . $quote->image);
			$file = $request->file('image');
			$filename = $file->getClientOriginalName();
			$file->move('images/', $filename);
			$quote->image = ('images/' . $filename);
		}
		$quote->save();
		return response()->json($quote->id);
	}
}
