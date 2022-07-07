<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Mail\VerifyEmail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
	public function createUser(RegisterRequest $request): JsonResponse
	{
		$validated = $request->validated();
		$validated['password'] = bcrypt($validated['password']);
		$validated['token'] = Str::random(60);
		$user = User::create($validated);
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
			return response()->json('User already verified', 404);
		}
		return response()->json('User doesnt exist', 404);
	}

	protected function respondWithToken(string $token): JsonResponse
	{
		return response()->json([
			'access_token' => $token,
			'token_type'   => 'bearer',
			'expires_in'   => auth()->factory()->getTTL() * 60,
		]);
	}
}
