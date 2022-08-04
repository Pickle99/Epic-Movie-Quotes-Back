<?php

namespace Tests\Feature;

use App\Http\Middleware\Authenticate;
use App\Models\Movie;
use App\Models\Quote;
use App\Models\User;
use Database\Seeders\GenresSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class MovieTest extends TestCase
{
	use DatabaseMigrations;

	/**
	 * A basic feature test example.
	 *
	 * @return void
	 */
	public function test_user_can_create_movie()
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

		$this->post(route('movies.store'), [
			'title_en'             => 'asdf',
			'title_ka'             => 'აბგს',
			'genres'               => [
				'Comedy', 'Drama',
			],
			'director_en'          => 'asasad',
			'director_ka'          => 'ასდასდ',
			'description_en'       => 'asfasf',
			'description_ka'       => 'ასდსაფ',
			'budget'               => 1234543,
			'image'                => UploadedFile::fake()->create('image.png', 1),
		])->assertSuccessful();
	}

	public function test_movie_description_is_accessable()
	{
		$this->withoutMiddleware([Authenticate::class]);

		$movie = Movie::factory()->create();
		Quote::factory()->create();
		$response = $this->get(route('movie.description', $movie->id));
		$response->assertSuccessful();
	}

	public function test_user_movies_is_accessable()
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
		Movie::factory()->create();
		Quote::factory()->create();

		$response = $this->get(route('user.movies'));
		$response->assertSuccessful();
	}

	public function test_exact_movie_is_accessable()
	{
		$this->withoutMiddleware([Authenticate::class]);
		$movie = Movie::factory()->create();
		Quote::factory()->create();
		$this->get(route('show.movie', $movie->id))->assertSuccessful();
	}

	public function test_user_can_update_his_own_movie()
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
		Quote::factory()->create();

		$this->post(route('movie.update', $movie->id), [
			'title_en'             => 'asdf',
			'title_ka'             => 'აბგს',
			'genres'               => [
				'Comedy',
			],
			'director_en'          => 'asasad',
			'director_ka'          => 'ასდასდ',
			'description_en'       => 'asfasf',
			'description_ka'       => 'ასდსაფ',
			'budget'               => 1234543,
			'image'                => UploadedFile::fake()->create('image.png', 1),
		])->assertSuccessful();
	}

	public function test_user_can_not_update_other_peoples_movies()
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
		]);
		Quote::factory()->create();

		$this->post(route('movie.update', $movie->id), [
			'title_en'             => 'asdf',
			'title_ka'             => 'აბგს',
			'genres'               => [
				'Comedy',
			],
			'director_en'          => 'asasad',
			'director_ka'          => 'ასდასდ',
			'description_en'       => 'asfasf',
			'description_ka'       => 'ასდსაფ',
			'budget'               => 1234543,
			'image'                => UploadedFile::fake()->create('image.png', 1),
		])->assertStatus(403);
	}

	public function test_user_can_delete_his_own_movie()
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
		Quote::factory()->create();

		$this->delete(route('movie.destroy', $movie->id), [
		])->assertSuccessful();
	}

	public function test_user_can_not_delete_other_peoples_movies()
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
		]);
		Quote::factory()->create();

		$this->delete(route('movie.destroy', $movie->id), [
		])->assertStatus(403);
	}
}
