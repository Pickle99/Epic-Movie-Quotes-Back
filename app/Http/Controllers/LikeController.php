<?php

namespace App\Http\Controllers;

use App\Events\AddOrRemoveLike;
use App\Models\Like;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
	public function store(Quote $quote): JsonResponse
	{
		$like = $this->like($quote);

		broadcast(new AddOrRemoveLike($like));
		return response()->json(['message' => 'Quote liked']);
	}

	private function like($quote)
	{
		$likeCheck = Like::where(['user_id' =>Auth::id(), 'quote_id' => $quote->id])->first();
		if ($likeCheck)
		{
			Like::where(['user_id' =>Auth::id(), 'quote_id' => $quote->id])->delete();
			return response()->json('deleted');
		}

		$like = new Like;
		$like->user_id = Auth::id();
		$like->quote_id = $quote->id;
		$like->save();
	}
}
