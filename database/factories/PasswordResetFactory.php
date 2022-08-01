<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PasswordReset>
 */
class PasswordResetFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition()
	{
		return [
			'email'   => User::factory()->create()->email,
			'user_id' => User::factory()->create()->id,
			'token'   => Str::random(60),
		];
	}
}
