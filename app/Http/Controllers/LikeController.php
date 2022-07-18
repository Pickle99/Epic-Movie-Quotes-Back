<?php

namespace App\Http\Controllers;

use App\Events\AddLike;
use App\Events\RemoveLike;
use App\Models\Like;
use App\Models\Quote;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
	public function store(Quote $quote)
	{
		$like = Like::where(['user_id' =>Auth::id(), 'quote_id' => $quote->id])->first();

		if ($like)
		{
			Like::where(['user_id' =>Auth::id(), 'quote_id' => $quote->id])->delete();
			broadcast(new RemoveLike($quote));
			return response()->json('Deleted');
		}
		$like = new Like;
		$like->user_id = Auth::id();
		$like->quote_id = $quote->id;
		$like->save();
		broadcast(new AddLike($like));
		return $like;
	}

//	public function like($quote)
//	{
//		$like = Like::where(['user_id' =>Auth::id(), 'quote_id' => $quote->id])->first();
//
//		if ($like)
//		{
//			Like::where(['user_id' =>Auth::id(), 'quote_id' => $quote->id])->delete();
//			return response()->json('Deleted');
//		}
//		$like = new Like;
//		$like->user_id = Auth::id();
//		$like->quote_id = $quote->id;
//		$like->save();
//		return response()->json('Added');
//	}
}
