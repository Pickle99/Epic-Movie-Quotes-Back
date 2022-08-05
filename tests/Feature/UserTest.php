<?php

namespace Tests\Feature;

use App\Http\Middleware\Authenticate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class UserTest extends TestCase
{
	use RefreshDatabase;

	/**
	 * A basic feature test example.
	 *
	 * @return void
	 */
	public function test_user_can_update_his_profile_information()
	{
		$email = 'admin@gmail.com';
		$password = '11111111';

		$user = User::factory()->create(
			[
				'email'             => $email,
				'password'          => bcrypt($password),
			]
		);

		$this->post(route('login'), [
			'user'                  => $email,
			'password'              => $password,
			'remember_token'        => false,
		]);

		$this->post(route('user.update', ['user' => $user->id]), [
			'username'              => 'jangobango',
			'email'                 => 'jangobango@yahoo.com',
			'password'              => '1231231231',
			'password_confirmation' => '1231231231',
			'avatar'                => UploadedFile::fake()->create('image.png', 1),
		])->assertSuccessful();
	}

	public function test_user_profile_is_accessible()
	{
		$this->withoutMiddleware([Authenticate::class]);
		$user = User::factory()->create();
		$this->get(route('user.show', $user->id))->assertSuccessful();
	}
}
