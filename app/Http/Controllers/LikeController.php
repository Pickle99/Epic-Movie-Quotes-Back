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
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
	public function store(Quote $quote): JsonResponse
	{
		$like = Like::where(['user_id' =>Auth::id(), 'quote_id' => $quote->id])->first();
		if ($like)
		{
			Like::where(['user_id' =>Auth::id(), 'quote_id' => $quote->id])->delete();
			broadcast(new RemoveLike($quote));
			return response()->json('Deleted');
		}
		$like = Like::create([
			'user_id'  => Auth::id(),
			'quote_id' => $quote->id,
		]);

		$quoteOwner = User::where('id', $quote->user_id)->first();

		if ($quote->user_id !== auth()->user()->id)
		{
			$notification = Notification::create([
				'action'             => 'like',
				'action_from'        => Auth::user()->username,
				'avatar'             => Auth::user()->avatar,
				'user_id'            => $quoteOwner->id,
				'quote_id'           => $quote->id,
				'comment_id'         => $like->id,
				'created_date'       => Carbon::now(),
				'notification_state' => 'New',
			]);

			broadcast(new ShowNotification($notification));
		}
		broadcast(new AddLike($like));
		return response()->json(['message' => 'Like added successfully']);
	}
}
