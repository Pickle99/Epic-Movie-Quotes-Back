<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
			'id'           => $this->id,
			'text'         => $this->text,
			'comment_from' => $this->comment_from,
			'avatar'       => $this->avatar,
			'user_id'      => $this->user_id,
			'quote_id'     => $this->quote_id,
			'created_at'   => $this->created_at,
			'updated_at'   => $this->updated_at,
		];
	}
}
