<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Mail\VerifyEmail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
	public function createUser(RegisterRequest $request): JsonResponse
	{
		$dir = 'images/avatar';
		if ($files = Storage::disk('web')->allFiles($dir))
		{
			$path = $files[array_rand($files)];
		}
		$user = User::create([
			'username' => $request->username,
			'email'    => $request->email,
			'password' => bcrypt($request->password),
			'token'    => Str::random(60),
			'avatar'   => $path,
		]);
		Mail::to($user->email)->send(new VerifyEmail($user));
		return response()->json('User successfully registered!', 200);
	}

	public function verifyEmail(string $token): JsonResponse
	{
		$user = User::where('token', $token)->first();
		if (isset($user))
		{
			if (!$user->email_verified_at)
			{
				$user->email_verified_at = Carbon::now();
				$user->save();
				$token = auth('api')->login($user);
				return $this->respondWithToken($token);
			}
			return response()->json(['error'=>'User already verified'], 404);
		}
		return response()->json(['error' => 'User doesnt exist'], 404);
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
