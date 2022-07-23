<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuoteResource extends JsonResource
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
			'id'         => $this->id,
			'text'       => $this->getTranslations('text'),
			'user'       => new UserResource($this->whenLoaded('user')),
			'movie'      => new MovieResource($this->whenLoaded('movie')),
			'likes'      => LikeResource::collection($this->likes),
			'comments'   => CommentResource::collection($this->comments),
			'image'      => $this->image,
			'movie_id'   => $this->movie_id,
			'user_id'    => $this->user_id,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
		];
	}
}
