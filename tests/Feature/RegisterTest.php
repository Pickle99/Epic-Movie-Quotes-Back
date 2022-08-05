<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
	use RefreshDatabase;

	/**
	 * A basic feature test example.
	 *
	 * @return void
	 */
	public function test_guest_can_register()
	{
		$this->post(route('register'), [
			'username'              => 'babakaka',
			'email'                 => 'babakaka@gmail.com',
			'password'              => '123123123',
			'password_confirmation' => '123123123',
		])->assertSuccessful();
	}

	public function test_user_see_lower_case_error_while_register()
	{
		$this->post(route('register'), [
			'username'              => 'babAKAKA',
			'email'                 => 'babakaka@gmail.com',
			'password'              => '123123123',
			'password_confirmation' => '123123123',
		])->assertSessionHasErrors();
	}

	public function test_user_can_verify_email()
	{
		$user = User::factory()->create([
			'email_verified_at' => null,
		]);

		$response = $this->post(route('user_verify', ['token' => $user->token]));

		$response->assertJsonStructure([
			'access_token', 'token_type', 'expires_in', 'user',
		]);
	}

	public function test_user_can_not_verify_email_if_already_verified()
	{
		$user = User::factory()->create();

		$response = $this->post(route('user_verify', ['token' => $user->token]));

		$response->assertStatus(404);
	}
}
