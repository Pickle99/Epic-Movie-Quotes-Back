<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ShowNotification implements ShouldBroadcast
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public $notification;

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct($notification)
	{
		$this->notification = $notification;

		$this->dontBroadcastToCurrentUser();
	}

	/**
	 * Get the channels the event should broadcast on.
	 *
	 * @return \Illuminate\Broadcasting\Channel|array
	 */
	public function broadcastOn()
	{
		return new PrivateChannel('showNotification.' . $this->notification->user_id);
	}
}
