<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
	public function login(LoginRequest $request): JsonResponse
	{
		$field = filter_var($request->user, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

		$token = auth()->attempt([
			$field     => $request->user,
			'password' => $request->password,
		]);

		if (!$token)
		{
			return response()->json(['error' => 'Wrong Credentials!'], 404);
		}

		return $this->respondWithToken($token);
	}

	public function logout(): JsonResponse
	{
		auth()->logout();
		return response()->json('Successfully logged out!');
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
