<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
	use RefreshDatabase;

	/*
	 * A basic feature test example.
	 *
	 * @return void
	 */
	public function test_auth_should_give_us_errors_if_input_is_not_provided()
	{
		$response = $this->post(route('login'));
		$response->assertSessionHasErrors(
			[
				'user',
				'password',
			]
		);
	}

	public function test_auth_should_give_us_user_error_if_we_wont_provide_user_input()
	{
		$response = $this->post(route('login'), [
			'password' => 'my-so-secret-password',
		]);

		$response->assertSessionHasErrors(
			[
				'user',
			]
		);
		$response->assertSessionDoesntHaveErrors(['password']);
	}

	public function test_auth_should_give_us_password_error_if_we_wont_provide_password_input()
	{
		$response = $this->post(route('login'), [
			'user' => 'gela@redberry.ge',
		]);

		$response->assertSessionHasErrors(
			[
				'password',
			]
		);
		$response->assertSessionDoesntHaveErrors(['user']);
	}

	public function test_auth_user_can_login_with_remember_true()
	{
		$email = 'admin@gmail.com';
		$password = '11111';

		User::factory()->create(
			[
				'email'             => $email,
				'password'          => bcrypt($password),
			]
		);

		$response = $this->post(route('login'), [
			'user'                  => $email,
			'password'              => $password,
			'remember_token'        => true,
		]);

		$response->assertJsonStructure([
			'access_token', 'token_type', 'expires_in',
		]);
	}

	public function test_auth_user_can_login_with_remember_false()
	{
		$email = 'admin@gmail.com';
		$password = '11111';

		User::factory()->create(
			[
				'email'             => $email,
				'password'          => bcrypt($password),
			]
		);

		$response = $this->post(route('login'), [
			'user'                  => $email,
			'password'              => $password,
			'remember_token'        => false,
		]);

		$response->assertJsonStructure([
			'access_token', 'token_type', 'expires_in',
		]);
	}

	public function test_auth_user_can_logout()
	{
		$email = 'babakaka@gmail.com';
		$password = '123123123';
		User::factory()->create(
			[
				'email'             => $email,
				'password'          => bcrypt($password),
			]
		);
		$token = auth()->attempt([
			'email'     => $email,
			'password'  => $password,
		]);
		$this->postJson(route('logout'), [], ['Authorization' => "Bearer $token"])
		->assertStatus(200);
	}

	public function test_auth_should_give_us_incorrect_credentials_error_when_such_user_does_not_exists()
	{
		$response = $this->post(route('login'), [
			'user'           => 'giuna@redberry.ge',
			'password'       => 'password',
			'remember_token' => false,
		]);

		$response->assertStatus(404);
	}
}
