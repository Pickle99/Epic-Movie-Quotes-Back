<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
	 */
	public function toArray($request)
	{
		return [
			'id'            => $this->id,
			'action'        => $this->action,
			'action_from'   => $this->action_from,
			'avatar'        => $this->avatar,
			'user_id'       => $this->user_id,
			'quote_id'      => $this->quote_id,
			'like_id'       => $this->like_id,
			'comment_id'    => $this->comment_id,
			'created_at'    => $this->created_at->diffForHumans(),
			'created_date'  => $this->created_date,
		];
	}
}
