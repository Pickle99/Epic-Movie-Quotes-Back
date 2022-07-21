<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
	const GOOGLE_TYPE = 'google';

	public function redirect(): JsonResponse
	{
		$url = Socialite::driver(static::GOOGLE_TYPE)->stateless()
			->redirect()->getTargetUrl();
		return response()->json(['url' => $url]);
	}

	public function callback(): JsonResponse
	{
		$socialUser = Socialite::driver(static::GOOGLE_TYPE)->stateless()->user();
		$user = User::where('email', $socialUser->email)->first();
		$dir = 'images/avatar';
		if ($files = Storage::disk('web')->allFiles($dir))
		{
			$path = $files[array_rand($files)];
		}
		if ($user)
		{
			$user->update([
				'provider'   => static::GOOGLE_TYPE,
				'provider_id'=> $socialUser->id,
			]);
		}
		if (!$user)
		{
			$user = User::create([
				'username'          => strtolower(str_replace(' ', '', $socialUser->name)),
				'email'             => $socialUser->email,
				'password'          => Hash::make($socialUser->getName() . '@' . $socialUser->getId()),
				'email_verified_at' => Carbon::now(),
				'avatar'            => $path,
				'provider'          => static::GOOGLE_TYPE,
				'provider_id'       => $socialUser->id,
			]);
		}
		$token = auth('api')->login($user);
		return $this->respondWithToken($token);
	}

//	private function createOrUpdateUser($data, $provider)
//	{
//		$user = User::where('email', $data->email)->first();
//
//		if ($user)
//		{
//			$user->update([
//				'provider'   => $provider,
//				'provider_id'=> $data->id,
//			]);
//		}
//		if (!$user)
//		{
//			$user = User::create([
//				'username'          => strtolower(str_replace(' ', '', $data->name)),
//				'email'             => $data->email,
//				'password'          => Hash::make($data->getName() . '@' . $data->getId()),
//				'email_verified_at' => Carbon::now(),
//				'provider'          => $provider,
//				'provider_id'       => $data->id,
//			]);
//		}
//		$token = auth('api')->login($user);
//		return $this->respondWithToken($token);
//	}

	protected function respondWithToken(string $token): JsonResponse
	{
		return response()->json([
			'access_token' => $token,
			'token_type'   => 'bearer',
			'expires_in'   => auth()->factory()->getTTL() * 60,
			'user'         => auth()->user(),
		]);
	}
}
