<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
	public function show(): JsonResponse
	{
		$userId = Auth::id();
		$notifications = Notification::where('user_id', $userId)->get();
		return response()->json(NotificationResource::collection($notifications));
	}

	public function markAsAllRead(): JsonResponse
	{
		$userId = Auth::id();
		$notifications = Notification::where('user_id', $userId)->get();
		foreach ($notifications as $notification)
		{
			$notification->notification_phase = null;
			$notification->save();
		}
		return response()->json(['message' => 'Notifications successfully marked as all read!']);
	}

	public function markSingleAsRead(Notification $notification): JsonResponse
	{
		$notification->notification_phase = null;
		$notification->save();
		return response()->json(['message' => 'Notification successfully marked as read!']);
	}
}
