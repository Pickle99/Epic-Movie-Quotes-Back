<?php

namespace Tests\Feature;

use App\Models\Like;
use App\Models\Notification;
use App\Models\Quote;
use App\Models\User;
use Database\Seeders\GenresSeeder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LikeTest extends TestCase
{
	use RefreshDatabase;

	/**
	 * A basic feature test example.
	 *
	 * @return void
	 */
	public function test_user_can_add_like_to_quote()
	{
		$email = 'admin@gmail.com';
		$password = '11111';

		$user = User::factory()->create(
			[
				'email'             => $email,
				'password'          => bcrypt($password),
			]
		);

		$this->post(route('login'), [
			'user'                  => $email,
			'password'              => $password,
			'remember_token'        => true,
		]);

		$quote = Quote::factory()->create([
			'user_id' => $user->id,
		]);
		$this->get(route('like.store', $quote->id))->assertSuccessful();
	}

	public function test_user_can_remove_like_to_quote()
	{
		$email = 'admin@gmail.com';
		$password = '11111';

		$user = User::factory()->create(
			[
				'email'             => $email,
				'password'          => bcrypt($password),
			]
		);

		$this->post(route('login'), [
			'user'                  => $email,
			'password'              => $password,
			'remember_token'        => true,
		]);
		$quote = Quote::factory()->create([
			'user_id' => $user->id,
		]);
		Like::factory()->create([
			'user_id'  => $user->id,
			'quote_id' => $quote->id,
		]);
		$this->get(route('like.store', $quote->id))->assertSuccessful();
	}

	public function test_user_can_see_notification_if_another_user_likes_his_post()
	{
		$email = 'admin@gmail.com';
		$password = '11111';

		$this->seed(GenresSeeder::class);

		$user = User::factory()->create([
			'email'    => $email,
			'password' => bcrypt($password),
		]);

		$this->post(route('login'), [
			'user'                  => $email,
			'password'              => $password,
			'remember_token'        => true,
		]);

		$quote = Quote::factory()->create([
			'user_id' => 2,
		]);

		$this->get(route('like.store', ['quote' => $quote->id]))->assertSuccessful();
	}

	public function test_like_belongs_to_quote_class()
	{
		$quote = Quote::factory()->create();
		$like = Like::factory()->create(['quote_id' => $quote->id]);

		$this->assertInstanceOf(BelongsTo::class, $like->quote());
	}

	public function test_like_belongs_to_user_class()
	{
		$user = User::factory()->create();
		$like = Like::factory()->create(['user_id' => $user->id]);

		$this->assertInstanceOf(BelongsTo::class, $like->user());
	}

	public function test_like_belongs_to_notification_class()
	{
		Notification::factory()->create();
		$like = Like::factory()->create();

		$this->assertInstanceOf(hasOne::class, $like->notification());
	}
}
