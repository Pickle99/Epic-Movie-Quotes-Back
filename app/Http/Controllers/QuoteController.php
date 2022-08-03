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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
	public function store(StoreQuoteRequest $request, Movie $movie): JsonResponse
	{
		if (auth()->user()->id !== $movie->user_id)
		{
			return response()->json(['forbidden' => 'You dont have permission to add quotes here'], 403);
		}

		$quote = Quote::create([
			'text' => [
				'en' => $request->text_en,
				'ka' => $request->text_ka,
			],
			'movie_id' => $movie->id,
			'user_id'  => auth()->user()->getAuthIdentifier(),
			'image'    => $request->image,
		]);
		if ($request->hasFile('image'))
		{
			File::delete(public_path('images/') . $quote->image);
			$file = $request->file('image');
			$filename = $file->getClientOriginalName();
			$file->move('images/', $filename);
			$quote->image = ('images/' . $filename);
			$quote->save();
		}

		return response()->json(['message' => 'Movie created successfully']);
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
		return response()->json(['message'=>'Quote updated successfully']);
	}

	public function showPaginatedQuotes(Request $request)
	{
		$str = $request->search;
		if (str_starts_with($str, 'q'))
		{
			$search = substr($str, 1);
			$quotes = Quote::with(['user', 'movie'])
				->where(DB::raw('lower(text)'), 'like', '%' . strtolower($search) . '%')
				->paginate(3);
			return QuoteResource::collection($quotes);
		}
		if (str_starts_with($str, 'm'))
		{
			$search = substr($str, 1);
			$quotes = Quote::with(['user', 'movie'])
				->whereHas('movie', function ($q) use ($search) {
					$q->where(DB::raw('lower(title)'), 'like', '%' . strtolower($search) . '%');
				})->latest('created_at')
				->paginate(3);
			return QuoteResource::collection($quotes);
		}
		$quotes = Quote::with(['user', 'movie'])->latest('created_at')->paginate(3);
		return QuoteResource::collection($quotes);
	}

	public function showAllQuotes()
	{
		$quotes = Quote::with(['user', 'movie'])->latest('created_at')->get();
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
		$quote = Quote::create([
			'text'     => ['en' => $request->text_en, 'ka' => $request->text_ka],
			'image'    => $request->image,
			'movie_id' => $request->movieId,
			'user_id'  => Auth::id(),
		]);
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
