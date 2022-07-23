<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\PasswordResetRequest;
use App\Mail\ResetPasswordMail;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
	public function resetPassword(ForgotPasswordRequest $request): JsonResponse
	{
		$token = Str::random(60);
		$user = User::where('email', $request->email)->first();
		$passwordReset = PasswordReset::create([
			'user_id'    => $user->id,
			'email'      => $request->email,
			'token'      => $token,
		]);
		Mail::to($request->email)->send(new ResetPasswordMail($passwordReset));
		return response()->json(['message' => 'Password reset email successfully sent to your email']);
	}

	public function updatePassword(PasswordResetRequest $request, string $token): JsonResponse
	{
		$passwordReset = PasswordReset::where('token', $token)->first();
		$user = User::find($passwordReset->user_id);
		$user->update([
			'password' => bcrypt($request->password),
		]);
		$passwordReset->delete();
		return response()->json(['message' => 'Password reset successfully']);
	}
}
