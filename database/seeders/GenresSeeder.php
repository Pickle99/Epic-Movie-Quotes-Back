<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenresSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('genres')->insert(
			[['name' => 'Comedy'], ['name' => 'Horror'], ['name' => 'Action'],
				['name'=> 'Thriller'], ['name' => 'Drama'], ['name'=>'Romantic'], ]
		);
	}
}
