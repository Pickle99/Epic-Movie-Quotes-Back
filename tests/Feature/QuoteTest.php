<?php

namespace Tests\Feature;

use App\Http\Middleware\Authenticate;
use App\Models\Movie;
use App\Models\Notification;
use App\Models\Quote;
use App\Models\User;
use Database\Seeders\GenresSeeder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class QuoteTest extends TestCase
{
	use RefreshDatabase;

	/**
	 * A basic feature test example.
	 *
	 * @return void
	 */
	public function test_user_can_add_quote_to_his_own_movie()
	{
		$email = 'admin@gmail.com';
		$password = '111111111';

		$user = User::factory()->create(
			[
				'username'          => 'blackjack',
				'email'             => $email,
				'password'          => bcrypt($password),
			]
		);

		$this->post(route('login'), [
			'user'                  => $email,
			'password'              => $password,
			'remember_token'        => false,
		]);

		$this->actingAs($user);

		$this->seed(GenresSeeder::class);

		$movie = Movie::factory()->create([
			'user_id' => $user->id,
		]);

		$this->post(route('quote.store', $movie->id), [
			'text_en'              => 'asdf',
			'text_ka'              => 'აბგს',
			'image'                => UploadedFile::fake()->create('image.png', 1),
		])->assertSuccessful();
	}

	public function test_user_can_not_add_quote_to_other_peoples_movie()
	{
		$email = 'admin@gmail.com';
		$password = '111111111';

		$user = User::factory()->create(
			[
				'username'          => 'blackjack',
				'email'             => $email,
				'password'          => bcrypt($password),
			]
		);

		$this->post(route('login'), [
			'user'                  => $email,
			'password'              => $password,
			'remember_token'        => false,
		]);

		$this->actingAs($user);

		$this->seed(GenresSeeder::class);

		$movie = Movie::factory()->create();

		$this->post(route('quote.store', $movie->id), [
			'text_en'              => 'asdf',
			'text_ka'              => 'აბგს',
			'image'                => UploadedFile::fake()->create('image.png', 1),
		])->assertStatus(403);
	}

	public function test_show_quote_is_accessable()
	{
		$this->withoutMiddleware([Authenticate::class]);
		$user = User::factory()->create();
		$quote = Quote::factory()->create([
			'user_id' => $user->id,
		]);
		$this->get(route('quote.get', $quote->id))->assertSuccessful();
	}

	public function test_user_can_not_update_other_peoples_quotes()
	{
		$email = 'admin@gmail.com';
		$password = '111111111';

		$user = User::factory()->create(
			[
				'username'          => 'blackjack',
				'email'             => $email,
				'password'          => bcrypt($password),
			]
		);

		$this->post(route('login'), [
			'user'                  => $email,
			'password'              => $password,
			'remember_token'        => false,
		]);

		$this->actingAs($user);
		$this->seed(GenresSeeder::class);
		$movie = Movie::factory()->create([]);
		Quote::factory()->create();

		$this->post(route('quote.update', $movie->id), [
			'text_en'              => 'asdf',
			'text_ka'              => 'აბგს',
			'image'                => UploadedFile::fake()->create('image.png', 1),
		])->assertStatus(403);
	}

	public function test_user_can_update_his_own_quote()
	{
		$email = 'admin@gmail.com';
		$password = '111111111';

		$user = User::factory()->create(
			[
				'username'          => 'blackjack',
				'email'             => $email,
				'password'          => bcrypt($password),
			]
		);

		$this->post(route('login'), [
			'user'                  => $email,
			'password'              => $password,
			'remember_token'        => false,
		]);

		$this->actingAs($user);
		$this->seed(GenresSeeder::class);
		$movie = Movie::factory()->create([
			'user_id' => $user->id,
		]);
		Quote::factory()->create([
			'user_id' => $user->id,
		]);

		$this->post(route('quote.update', $movie->id), [
			'text_en'              => 'asdf',
			'text_ka'              => 'აბგს',
			'image'                => UploadedFile::fake()->create('image.png', 1),
		])->assertSuccessful();
	}

	public function test_paginated_quotes_for_feed_is_accessable_and_search_in_quotes_is_working()
	{
		$this->withoutMiddleware([Authenticate::class]);
		$movie = Movie::factory()->create();
		$user = User::factory()->create();
		Quote::factory()->count(10)->create([
			'text' => [
				'en' => 'jajaja',
				'ka' => 'ჯაჯაჯა',
			],
			'movie_id' => $movie->id,
			'user_id'  => $user->id,
		]);
		$this->post(route('feed.show'), [
			'search' => 'qjaja',
		])->assertSuccessful();
	}

	public function test_paginated_quotes_for_feed_is_accessable_and_search_in_movies_is_working()
	{
		$this->withoutMiddleware([Authenticate::class]);
		$movie = Movie::factory()->create();
		$user = User::factory()->create();
		Quote::factory()->count(10)->create([
			'text' => [
				'en' => 'jajaja',
				'ka' => 'ჯაჯაჯა',
			],
			'movie_id' => $movie->id,
			'user_id'  => $user->id,
		]);
		$this->post(route('feed.show'), [
			'search' => 'mjaja',
		])->assertSuccessful();
	}

	public function test_paginated_quotes_for_feed_is_accessable_if_search_is_not_provided()
	{
		$this->withoutMiddleware([Authenticate::class]);
		$movie = Movie::factory()->create();
		$user = User::factory()->create();
		Quote::factory()->count(10)->create([
			'text' => [
				'en' => 'jajaja',
				'ka' => 'ჯაჯაჯა',
			],
			'movie_id' => $movie->id,
			'user_id'  => $user->id,
		]);
		$this->post(route('feed.show'))->assertSuccessful();
	}

	public function test_all_quotes_is_accessible()
	{
		$this->withoutMiddleware([Authenticate::class]);
		$user = User::factory()->create();
		$movie = Movie::factory()->create([
			'user_id' => $user->id,
		]);
		Quote::factory()->create([
			'user_id'  => $user->id,
			'movie_id' => $movie->id,
		]);
		$this->get(route('quotes'))->assertSuccessful();
	}

	public function test_user_can_delete_his_own_quote()
	{
		$email = 'admin@gmail.com';
		$password = '111111111';

		$user = User::factory()->create(
			[
				'username'          => 'blackjack',
				'email'             => $email,
				'password'          => bcrypt($password),
			]
		);

		$this->post(route('login'), [
			'user'                  => $email,
			'password'              => $password,
			'remember_token'        => false,
		]);

		$this->actingAs($user);
		$quote = Quote::factory()->create([
			'user_id' => $user->id,
		]);
		Quote::factory()->create();

		$this->delete(route('quote.destroy', $quote->id), [
		])->assertSuccessful();
	}

	public function test_user_can_not_delete_other_peoples_own_quotes()
	{
		$email = 'admin@gmail.com';
		$password = '111111111';

		$user = User::factory()->create(
			[
				'username'          => 'blackjack',
				'email'             => $email,
				'password'          => bcrypt($password),
			]
		);

		$this->post(route('login'), [
			'user'                  => $email,
			'password'              => $password,
			'remember_token'        => false,
		]);

		$this->actingAs($user);
		$quote = Quote::factory()->create();
		Quote::factory()->create();

		$this->delete(route('quote.destroy', $quote->id), [
		])->assertStatus(403);
	}

	public function test_user_can_write_new_quote_from_feed_page()
	{
		$email = 'admin@gmail.com';
		$password = '111111111';

		$user = User::factory()->create(
			[
				'username'          => 'blackjack',
				'email'             => $email,
				'password'          => bcrypt($password),
			]
		);

		$this->post(route('login'), [
			'user'                  => $email,
			'password'              => $password,
			'remember_token'        => false,
		]);

		$this->actingAs($user);

		$this->seed(GenresSeeder::class);

		$movie = Movie::factory()->create([
			'user_id' => $user->id,
		]);

		$this->post(route('quote.store_write'), [
			'text_en'              => 'asdf',
			'text_ka'              => 'აბგს',
			'movieId'              => $movie->id,
			'image'                => UploadedFile::fake()->create('image.png', 1),
		])->assertSuccessful();
	}

	public function test_quote_has_one_notification_class()
	{
		$quote = Quote::factory()->create();
		Notification::factory()->create(['quote_id' => $quote->id]);

		$this->assertInstanceOf(HasOne::class, $quote->notification());
	}
}
