<?php

namespace App\Http\Controllers;

use App\Events\AddComment;
use App\Events\ShowNotification;
use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
	public function store(StoreCommentRequest $request, Quote $quote): JsonResponse
	{
		$comment = Comment::create([
			'text'         => $request->text,
			'user_id'      => Auth::id(),
			'quote_id'     => $quote->id,
			'comment_from' => Auth::user()->username,
			'avatar'       => Auth::user()->avatar,
		]);

		$quoteOwner = User::where('id', $quote->user_id)->first();

		if ($quote->user_id !== auth()->user()->id)
		{
			$notification = Notification::create([
				'action'             => 'comment',
				'action_from'        => Auth::user()->username,
				'avatar'             => Auth::user()->avatar,
				'user_id'            => $quoteOwner->id,
				'quote_id'           => $quote->id,
				'comment_id'         => $comment->id,
				'created_date'       => now(),
				'notification_state' => 'New',
			]);

			broadcast(new ShowNotification($notification));
		}
		broadcast(new AddComment($comment));
		return response()->json(['message' => 'Comment added successfully']);
	}
}
