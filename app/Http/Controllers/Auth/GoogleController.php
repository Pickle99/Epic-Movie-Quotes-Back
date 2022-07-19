<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
	const GOOGLE_TYPE = 'google';

	public function redirect(): RedirectResponse
	{
		//stateless
		return Socialite::driver(static::GOOGLE_TYPE)->redirect();
	}

	public function callback(): RedirectResponse
	{
		$user = Socialite::driver(static::GOOGLE_TYPE)->user();
		$this->createOrUpdateUser($user, static::GOOGLE_TYPE);
		return redirect()->to('http://localhost:3000/feed');
	}

	private function createOrUpdateUser($data, $provider)
	{
		$user = User::where('email', $data->email)->first();

		if ($user)
		{
			$user->update([
				'provider'   => $provider,
				'provider_id'=> $data->id,
			]);
		}
		if (!$user)
		{
			$user = User::create([
				'username'          => strtolower(str_replace(' ', '', $data->name)),
				'email'             => $data->email,
				'password'          => Hash::make($data->getName() . '@' . $data->getId()),
				'email_verified_at' => Carbon::now(),
				'provider'          => $provider,
				'provider_id'       => $data->id,
			]);
		}
		auth()->login($user, true);
	}
}
