<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Movie;
use App\Models\Notification;
use App\Models\PasswordReset;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserModelTest extends TestCase
{
	/*
	 * A basic feature test example.
	 *
	 * @return void
	 */
	use RefreshDatabase;

	public function test_user_has_one_reset_password_class()
	{
		$user = User::factory()->create();
		PasswordReset::factory()->create(['user_id' => $user->id]);

		$this->assertInstanceOf(HasOne::class, $user->passwordReset());
	}

	public function test_user_has_many_movie_class()
	{
		$user = User::factory()->create();
		Movie::factory()->create(['user_id' => $user->id]);

		$this->assertInstanceOf(HasMany::class, $user->movies());
	}

	public function test_user_has_many_quote_class()
	{
		$user = User::factory()->create();
		Quote::factory()->create(['user_id' => $user->id]);

		$this->assertInstanceOf(HasMany::class, $user->quotes());
	}

	public function test_user_has_many_like_class()
	{
		$user = User::factory()->create();
		Like::factory()->create(['user_id' => $user->id]);

		$this->assertInstanceOf(HasMany::class, $user->likes());
	}

	public function test_user_has_many_comment_class()
	{
		$user = User::factory()->create();
		Comment::factory()->create(['user_id' => $user->id]);

		$this->assertInstanceOf(HasMany::class, $user->comments());
	}

	public function test_user_has_many_notification_class()
	{
		$user = User::factory()->create();
		Notification::factory()->create(['user_id' => $user->id]);

		$this->assertInstanceOf(HasMany::class, $user->notification());
	}
}
