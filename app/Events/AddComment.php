<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AddComment implements ShouldBroadcast
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public $comment;

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct($comment)
	{
		$this->comment = $comment;
	}

	/**
	 * Get the channels the event should broadcast on.
	 *
	 * @return \Illuminate\Broadcasting\Channel|array
	 */
	public function broadcastOn()
	{
		return new Channel('addComment.' . $this->comment->quote_id);
	}
}
