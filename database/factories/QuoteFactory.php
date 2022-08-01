<?php

namespace Database\Factories;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quote>
 */
class QuoteFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition()
	{
		return [
			'text' => [
				'en' => $this->faker->sentence,
				'ka' => $this->faker->sentence,
			],
			'image'    => $this->faker->name,
			'movie_id' => Movie::factory()->create()->id,
			'user_id'  => User::factory()->create()->id,
		];
	}
}
