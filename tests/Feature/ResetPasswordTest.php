<?php

namespace Tests\Feature;

use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
	use RefreshDatabase;

	/**
	 * A basic feature test example.
	 *
	 * @return void
	 */
	public function test_guest_can_get_reset_password_mail_for_his_account()
	{
		$user = User::factory()->create([
			'email' => 'babakaka@gmail.com',
		]);
		$this->post(route('password.forgot'), [
			'email' => $user->email,
		])->assertSuccessful();
	}

	public function test_guest_can_update_password_after()
	{
		$user = User::factory()->create([
			'email' => 'babakaka@gmail.com',
		]);

		PasswordReset::create([
			'email'   => $user->email,
			'token'   => $user->token,
			'user_id' => $user->id,
		]);
		$this->post(route('password.reset', ['token' => $user->token]), [
			'password'              => '123123123',
			'password_confirmation' => '123123123',
			'email'                 => $user->email,
			'token'                 => $user->token,
		])->assertSuccessful();
	}

	public function test_reset_password_belongs_to_user_class()
	{
		$user = User::factory()->create();
		$reset = PasswordReset::factory()->create(['user_id' => $user->id]);

		$this->assertInstanceOf(BelongsTo::class, $reset->user());
	}
}
