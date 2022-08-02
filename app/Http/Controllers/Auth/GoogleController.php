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
	public function redirect(): JsonResponse
	{
		$url = Socialite::driver('google')->stateless()
			->redirect()->getTargetUrl();
		return response()->json(['url' => $url]);
	}

	public function callback(): JsonResponse
	{
		$socialUser = Socialite::driver('google')->stateless()->user();
		$user = User::where('email', $socialUser->email)->first();
		$dir = 'images/avatar';
		if ($files = Storage::disk('web')->allFiles($dir))
		{
			$path = $files[array_rand($files)];
		}
		if ($user)
		{
			$user->update([
				'provider'   => 'google',
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
				'provider'          => 'google',
				'provider_id'       => $socialUser->id,
			]);
		}
		$token = auth()->login($user);
		return $this->respondWithToken($token);
	}

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
