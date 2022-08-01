<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class NotificationFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition()
	{
		return [
			'action'       => $this->faker->name,
			'action_from'  => $this->faker->name,
			'avatar'       => $this->faker->name,
			'user_id'      => User::factory()->create()->id,
			'quote_id'     => Quote::factory()->create()->id,
			'like_id'      => Like::factory()->create()->id,
			'comment_id'   => Comment::factory()->create()->id,
			'created_date' => now(),
		];
	}
}
