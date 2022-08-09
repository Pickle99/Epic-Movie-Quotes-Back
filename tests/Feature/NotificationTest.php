<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Notification;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationTest extends TestCase
{
	use RefreshDatabase;

	/*
	 * A basic feature test example.
	 *
	 * @return void
	 */
	public function test_user_notifications_are_accessible()
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

		Notification::factory()->create([
			'user_id' => $user->id,
		]);

		$this->get(route('notifications.show'))->assertSuccessful();
	}

	public function test_user_can_mark_as_all_read_all_his_notifications()
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

		Notification::factory()->create([
			'user_id' => $user->id,
		]);

		$this->get(route('notifications-all-read'))->assertSuccessful();
	}

	public function test_user_can_mark_as_read_only_one_notification_which_he_choose()
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

		$notification = Notification::factory()->create([
			'user_id' => $user->id,
		]);

		$this->get(route('notifications-single-read', ['notification' => $notification->id]))->assertSuccessful();
	}

	public function test_notification_belongs_to_user_class()
	{
		$user = User::factory()->create();
		$notification = Notification::factory()->create(['user_id' => $user->id]);

		$this->assertInstanceOf(BelongsTo::class, $notification->user());
	}

	public function test_notification_belongs_to_quote_class()
	{
		$quote = Quote::factory()->create();
		$notification = Notification::factory()->create(['quote_id' => $quote->id]);

		$this->assertInstanceOf(BelongsTo::class, $notification->quote());
	}

	public function test_notification_belongs_to_like_class()
	{
		$like = Like::factory()->create();
		$notification = Notification::factory()->create([
			'like_id' => $like->id,
		]);

		$this->assertInstanceOf(BelongsTo::class, $notification->like());
	}

	public function test_notification_belongs_to_comment_class()
	{
		$comment = Comment::factory()->create();
		$notification = Notification::factory()->create([
			'comment_id' => $comment->id,
		]);

		$this->assertInstanceOf(BelongsTo::class, $notification->comment());
	}
}
