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
}
