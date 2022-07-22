<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource
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
			'id'          => $this->id,
			'title'       => $this->getTranslations('title'),
			'description' => $this->getTranslations('description'),
			'director'    => $this->getTranslations('director'),
			'user'        => new UserResource($this->user),
			'quotes'      => QuoteResource::collection($this->whenLoaded('quotes')),
			'genres'      => GenreResource::collection($this->genres),
			'year'        => $this->year,
			'budget'      => $this->budget,
			'image'       => $this->image,
			'user_id'     => $this->user_id,
			'created_at'  => $this->created_at,
			'updated_at'  => $this->updated_at,
		];
	}
}
