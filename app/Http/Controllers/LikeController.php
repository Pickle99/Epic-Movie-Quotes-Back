<?php

namespace App\Http\Controllers;

use App\Events\AddLike;
use App\Events\RemoveLike;
use App\Events\ShowNotification;
use App\Models\Like;
use App\Models\Notification;
use App\Models\Quote;
use App\Models\User;
use Carbon\Carbon;
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
		$quoteOwner = User::where('id', $quote->user_id)->first();
		$notification = new Notification;
		$notification->action = 'like';
		$notification->action_from = Auth::user()->username;
		$notification->avatar = Auth::user()->avatar;
		$notification->user_id = $quoteOwner->id;
		$notification->quote_id = $quote->id;
		$notification->like_id = $like->id;
		$notification->created_date = Carbon::now();
		$notification->save();
		broadcast(new ShowNotification($notification));
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
