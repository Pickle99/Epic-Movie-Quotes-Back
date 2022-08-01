<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movie>
 */
class MovieFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition()
	{
		return [
			'title' => [
				'en' => $this->faker->sentence,
				'ka' => $this->faker->sentence,
			],
			'director' => [
				'en' => $this->faker->sentence,
				'ka' => $this->faker->sentence,
			],
			'description' => [
				'en' => $this->faker->sentence,
				'ka' => $this->faker->sentence,
			],
			'user_id' => User::factory()->create()->id,
			'year'    => $this->faker->numberBetween([1, 1999]),
			'budget'  => $this->faker->numberBetween([1232, 31312412553]),
			'image'   => $this->faker->name,
		];
	}
}
