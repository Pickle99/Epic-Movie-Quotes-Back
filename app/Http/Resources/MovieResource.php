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
			'year'        => $this->year,
			'budget'      => $this->budget,
			'image'       => $this->image,
			'created_at'  => $this->created_at,
		];
	}
}
