<?php

namespace Tests\Feature;

use App\Http\Middleware\Authenticate;
use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class GenreTest extends TestCase
{
	use DatabaseMigrations;

	/**
	 * A basic feature test example.
	 *
	 * @return void
	 */
	public function test_genres_is_accessible()
	{
		$this->withoutMiddleware([Authenticate::class]);
		$this->get(route('genres'))->assertSuccessful();
	}

	public function test_genre_class_belongs_to_many_movie_class()
	{
		$genre = Genre::create([
			'name' => 'Comedy',
		]);
		Movie::factory()->count(5)->create();

		$this->assertInstanceOf(BelongsToMany::class, $genre->movies());
	}
}
