<?php

namespace App\Http\Controllers;

use App\Events\AddComment;
use App\Events\ShowNotification;
use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\Quote;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
	public function store(StoreCommentRequest $request, Quote $quote)
	{
		$comment = new Comment();
		$comment->text = $request->text;
		$comment->user_id = Auth::id();
		$comment->quote_id = $quote->id;
		$comment->comment_from = Auth::user()->username;
		$comment->avatar = Auth::user()->avatar;
		$comment->save();

		$quoteOwner = User::where('id', $quote->user_id)->first();

		if ($quote->user_id !== auth()->user()->id)
		{
			$notification = new Notification;
			$notification->action = 'comment';
			$notification->action_from = Auth::user()->username;
			$notification->avatar = Auth::user()->avatar;
			$notification->user_id = $quoteOwner->id;
			$notification->quote_id = $quote->id;
			$notification->comment_id = $comment->id;
			$notification->created_date = Carbon::now();
			$notification->notification_state = 'New';
			$notification->save();

			broadcast(new ShowNotification($notification));
		}
		broadcast(new AddComment($comment));
		return response()->json(['message' => 'Comment added successfully']);
	}
}
