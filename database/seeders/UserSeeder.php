<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('user')->insert(
			[[
				'username'              => 'jonathan',
				'email'                 => 'jonathan@gmail.com',
				'email_verified_at'     => now(),
				'password'              => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
				'provider'              => null,
				'provider_id'           => null,
				'avatar'                => 'mile_25.jpg',
				'token'                 => Str::random(60),
				'remember_token'        => Str::random(10),
			],
				[
					'username'              => 'abraham',
					'email'                 => 'abraham@gmail.com',
					'email_verified_at'     => now(),
					'password'              => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
					'provider'              => null,
					'provider_id'           => null,
					'avatar'                => 'mile_25.jpg',
					'token'                 => Str::random(60),
					'remember_token'        => Str::random(10),
				],
			]
		);
	}
}
