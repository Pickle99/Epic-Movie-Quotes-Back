<?php

namespace Database\Factories;

use App\Models\Quote;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition()
	{
		return [
			'text'         => $this->faker->sentence,
			'comment_from' => $this->faker->name,
			'avatar'       => $this->faker->name,
			'user_id'      => User::factory()->create()->id,
			'quote_id'     => Quote::factory()->create()->id,
		];
	}
}
