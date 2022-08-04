<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Notification;
use App\Models\Quote;
use App\Models\User;
use Database\Seeders\GenresSeeder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CommentTest extends TestCase
{
	use DatabaseMigrations;

	/**
	 * A basic feature test example.
	 *
	 * @return void
	 */
	public function test_user_can_add_comment_to_quote()
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
		$this->post(route('comment.store', $quote->id), [
			'text' => 'Amazing movie!',
		])->assertSuccessful();
	}

	public function test_user_can_see_notification_if_another_user_comments_on_his_post()
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

		$this->post(route('comment.store', ['quote' => $quote->id]), [
			'text' => 'Awesome!',
		])->assertSuccessful();
	}

	public function test_comment_belongs_to_quote_class()
	{
		$quote = Quote::factory()->create();
		$comment = Comment::factory()->create(['quote_id' => $quote->id]);

		$this->assertInstanceOf(BelongsTo::class, $comment->quote());
	}

	public function test_comment_belongs_to_user_class()
	{
		$user = User::factory()->create();
		$comment = Comment::factory()->create(['user_id' => $user->id]);

		$this->assertInstanceOf(BelongsTo::class, $comment->user());
	}

	public function test_comment_belongs_to_notification_class()
	{
		Notification::factory()->create();
		$comment = Comment::factory()->create();

		$this->assertInstanceOf(BelongsTo::class, $comment->notification());
	}
}
